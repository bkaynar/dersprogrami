<?php

namespace App\Imports;

use App\Models\Ogretmen;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

class OgretmenMusaitlikImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use Importable;

    protected $errors = [];
    protected $successCount = 0;
    protected $updateCount = 0;
    protected $zamanDilimleri;
    protected $zamanDilimMap = [];

    public function __construct()
    {
        // Zaman dilimlerini yükle ve map'le
        $this->zamanDilimleri = ZamanDilim::all()->sortBy([
            ['gun_sirasi', 'asc'],
            ['baslangic_saati', 'asc'],
        ])->values();

        // Başlık formatından zaman dilimine map oluştur
        foreach ($this->zamanDilimleri as $zd) {
            $key = strtolower(
                $zd->haftanin_gunu . ' ' .
                substr($zd->baslangic_saati, 0, 5) . '-' .
                substr($zd->bitis_saati, 0, 5)
            );
            $this->zamanDilimMap[$key] = $zd->id;
        }
    }

    /**
     * Excel verilerini işle
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Öğretmen adını al
                $ogretmenAdi = $row['ogretmen'] ?? $row['ögretmen'] ?? null;

                if (empty($ogretmenAdi)) {
                    $this->errors[] = 'Öğretmen adı boş olamaz';
                    continue;
                }

                // Öğretmeni bul
                $ogretmen = Ogretmen::where('isim', 'like', "%{$ogretmenAdi}%")->first();

                if (!$ogretmen) {
                    $this->errors[] = "Öğretmen bulunamadı: {$ogretmenAdi}";
                    continue;
                }

                // Bu öğretmenin mevcut müsaitliklerini sil
                OgretmenMusaitlik::where('ogretmen_id', $ogretmen->id)->delete();

                $musaitlikSayisi = 0;

                // Her sütunu kontrol et (öğretmen sütunu hariç)
                foreach ($row as $key => $value) {
                    if ($key === 'ogretmen' || $key === 'ögretmen') {
                        continue;
                    }

                    // Değer 1 ise müsait demektir
                    if ($value == '1' || $value === 1 || strtolower($value) === 'evet' || strtolower($value) === 'yes') {
                        // Sütun başlığından zaman dilimini bul
                        $zamanDilimId = $this->findZamanDilimFromHeader($key);

                        if ($zamanDilimId) {
                            OgretmenMusaitlik::create([
                                'ogretmen_id' => $ogretmen->id,
                                'zaman_dilimi_id' => $zamanDilimId,
                                'musaitlik_tipi' => 'musait',
                            ]);
                            $musaitlikSayisi++;
                        }
                    }
                }

                if ($musaitlikSayisi > 0) {
                    $this->successCount++;
                } else {
                    $this->updateCount++;
                }
            } catch (\Exception $e) {
                $this->errors[] = "Satır işlenirken hata: {$e->getMessage()}";
            }
        }
    }

    /**
     * Sütun başlığından zaman dilimi ID'sini bul
     */
    protected function findZamanDilimFromHeader(string $header): ?int
    {
        // Başlığı temizle ve normalize et
        $normalized = strtolower(trim($header));
        $normalized = str_replace(["\n", "\r", '  '], ' ', $normalized);

        // Map'te ara
        return $this->zamanDilimMap[$normalized] ?? null;
    }

    /**
     * Hata durumunda
     */
    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    /**
     * İstatistikleri al
     */
    public function getStats(): array
    {
        return [
            'success' => $this->successCount,
            'updated' => $this->updateCount,
            'errors' => count($this->errors),
            'total' => $this->successCount + $this->updateCount,
        ];
    }

    /**
     * Hata detaylarını al
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
