<?php

namespace App\Imports;

use App\Models\Ogretmen;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ToArray;

class OgretmenMusaitlikSheetImport implements ToArray
{
    protected $ogretmen;
    protected $parentImport;

    public function __construct(Ogretmen $ogretmen, $parentImport)
    {
        $this->ogretmen = $ogretmen;
        $this->parentImport = $parentImport;
    }

    public function array(array $array): void
    {
        try {
            // Mevcut müsaitlikleri sil
            OgretmenMusaitlik::where('ogretmen_id', $this->ogretmen->id)->delete();

            // Zaman dilimlerini al
            $zamanDilimleri = ZamanDilim::all()->keyBy(function ($zd) {
                return $zd->haftanin_gunu . '_' . substr($zd->baslangic_saati, 0, 5);
            });

            $gunIsimleri = [
                'PAZARTESİ' => 'pazartesi',
                'SALI' => 'sali',
                'ÇARŞAMBA' => 'carsamba',
                'PERŞEMBE' => 'persembe',
                'CUMA' => 'cuma',
                'CUMARTESİ' => 'cumartesi',
                'PAZAR' => 'pazar',
            ];

            $currentGun = null;
            $processedCount = 0;

            foreach ($array as $rowIndex => $row) {
                // Boş satırları atla
                if (empty($row) || !isset($row[0]) || trim($row[0]) === '') {
                    continue;
                }

                $firstCell = trim($row[0]);

                // Gün başlığı mı?
                if (isset($gunIsimleri[$firstCell])) {
                    $currentGun = $gunIsimleri[$firstCell];
                    continue;
                }

                // Saat aralığı mı? (örn: "08:00 - 08:45")
                if (preg_match('/^(\d{2}:\d{2})\s*-\s*\d{2}:\d{2}$/', $firstCell, $matches)) {
                    if ($currentGun === null) {
                        continue;
                    }

                    $baslangicSaati = $matches[1];
                    $key = $currentGun . '_' . $baslangicSaati;

                    if (!isset($zamanDilimleri[$key])) {
                        continue;
                    }

                    $zamanDilimi = $zamanDilimleri[$key];

                    // İkinci sütunda X var mı?
                    $musaitDegil = isset($row[1]) && strtoupper(trim($row[1])) === 'X';

                    // Müsaitlik kaydı oluştur
                    OgretmenMusaitlik::create([
                        'ogretmen_id' => $this->ogretmen->id,
                        'zaman_dilimi_id' => $zamanDilimi->id,
                        'musaitlik_tipi' => $musaitDegil ? 'musait_degil' : 'musait',
                    ]);

                    $processedCount++;
                }
            }

            if ($processedCount > 0) {
                $this->parentImport->addSuccess();
            } else {
                $this->parentImport->addError("Öğretmen: {$this->ogretmen->isim} - Hiç veri işlenemedi");
            }

        } catch (\Exception $e) {
            $this->parentImport->addError("Öğretmen: {$this->ogretmen->isim} - Hata: " . $e->getMessage());
        }
    }
}
