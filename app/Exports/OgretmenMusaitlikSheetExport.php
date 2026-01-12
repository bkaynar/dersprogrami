<?php

namespace App\Exports;

use App\Models\Ogretmen;
use App\Models\OgretmenDers;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OgretmenMusaitlikSheetExport implements FromArray, WithStyles, WithTitle
{
    protected $ogretmen;
    protected $zamanDilimleri;
    protected $mevcutMusaitlikler;
    protected $gerekliDersSaati;

    public function __construct(Ogretmen $ogretmen)
    {
        $this->ogretmen = $ogretmen;

        // Zaman dilimlerini gün ve saate göre sırala
        $this->zamanDilimleri = ZamanDilim::all()->sortBy([
            ['gun_sirasi', 'asc'],
            ['baslangic_saati', 'asc'],
        ])->values();

        // Mevcut müsaitlikleri al
        $this->mevcutMusaitlikler = OgretmenMusaitlik::where('ogretmen_id', $ogretmen->id)
            ->get()
            ->keyBy('zaman_dilimi_id');

        // Gerekli ders saatini hesapla
        $this->gerekliDersSaati = $this->calculateRequiredHours();
    }

    /**
     * Sheet başlığı (tab ismi)
     */
    public function title(): string
    {
        // Excel tab ismi için özel karakterleri temizle
        $isim = preg_replace('/[^\w\s-]/', '', $this->ogretmen->isim);
        return substr($isim, 0, 31); // Excel tab ismi max 31 karakter
    }

    /**
     * Excel verilerini oluştur
     */
    public function array(): array
    {
        $data = [];

        // Başlık bilgileri
        $data[] = ['ÖĞRETMEN MÜSAİTLİK FORMU', '', ''];
        $data[] = ['', '', ''];
        $data[] = ['Öğretmen:', $this->ogretmen->isim, ''];
        $data[] = ['Unvan:', $this->ogretmen->unvan ?? '', ''];
        $data[] = ['E-posta:', $this->ogretmen->email ?? '', ''];
        $data[] = ['Gerekli Ders Saati:', $this->gerekliDersSaati . ' saat/hafta', ''];
        $data[] = ['', '', ''];
        $data[] = ['AÇIKLAMA:', 'Müsait OLMADIĞINIZ saatlere "X" koyun.', 'Boş = Müsait'];
        $data[] = ['', '', ''];

        // Günleri grupla
        $gunSirasi = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma', 'cumartesi', 'pazar'];
        $gunIsimleri = [
            'pazartesi' => 'PAZARTESİ',
            'sali' => 'SALI',
            'carsamba' => 'ÇARŞAMBA',
            'persembe' => 'PERŞEMBE',
            'cuma' => 'CUMA',
            'cumartesi' => 'CUMARTESİ',
            'pazar' => 'PAZAR',
        ];

        $zamanDilimleriByGun = [];
        foreach ($this->zamanDilimleri as $zd) {
            $zamanDilimleriByGun[$zd->haftanin_gunu][] = $zd;
        }

        // Her gün için tablo oluştur
        foreach ($gunSirasi as $gun) {
            if (!isset($zamanDilimleriByGun[$gun]) || empty($zamanDilimleriByGun[$gun])) {
                continue;
            }

            // Gün başlığı
            $data[] = [$gunIsimleri[$gun], '', ''];
            $data[] = ['Saat Aralığı', 'Müsait Değil (X)', 'Mevcut Durum'];

            // Bu günün zaman dilimlerini ekle
            foreach ($zamanDilimleriByGun[$gun] as $zd) {
                $saatAraligi = substr($zd->baslangic_saati, 0, 5) . ' - ' . substr($zd->bitis_saati, 0, 5);

                // Mevcut durumu kontrol et
                $mevcutDurum = '';
                if (isset($this->mevcutMusaitlikler[$zd->id])) {
                    $tip = $this->mevcutMusaitlikler[$zd->id]->musaitlik_tipi;
                    $mevcutDurum = $tip === 'musait' ? 'Müsait' :
                                  ($tip === 'musait_degil' ? 'Müsait Değil' : 'Tercih Edilen');
                } else {
                    $mevcutDurum = 'Tanımsız';
                }

                // Mevcut duruma göre X koy
                $xIslaret = (isset($this->mevcutMusaitlikler[$zd->id]) &&
                           $this->mevcutMusaitlikler[$zd->id]->musaitlik_tipi === 'musait_degil') ? 'X' : '';

                $data[] = [$saatAraligi, $xIslaret, $mevcutDurum];
            }

            $data[] = ['', '', '']; // Boş satır
        }

        // Alt bilgi
        $data[] = [];
        $data[] = ['NOTLAR:'];
        $data[] = ['• Müsait olmadığınız saatlere "X" işareti koyun'];
        $data[] = ['• Boş bırakılan saatler müsait kabul edilir'];
        $data[] = ['• Gerekli ders saatiniz: ' . $this->gerekliDersSaati . ' saat/hafta'];
        $data[] = ['• En az ' . $this->gerekliDersSaati . ' saat müsait olmanız önerilir'];

        return $data;
    }

    /**
     * Stil ayarları
     */
    public function styles(Worksheet $sheet)
    {
        // Ana başlık
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E75B6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->mergeCells('A1:C1');
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Öğretmen bilgileri
        $sheet->getStyle('A3:A6')->getFont()->setBold(true);
        $sheet->getStyle('B6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'D00000'],
            ],
        ]);

        // Açıklama
        $sheet->getStyle('A8:B8')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => true,
                'color' => ['rgb' => '0066CC'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6F3FF'],
            ],
        ]);
        $sheet->mergeCells('A8:B8');

        $sheet->getStyle('C8')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => true,
                'color' => ['rgb' => '006600'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6FFE6'],
            ],
        ]);

        // Sütun genişlikleri
        $sheet->getColumnDimension('A')->setWidth(18);  // Saat aralığı
        $sheet->getColumnDimension('B')->setWidth(15);  // X işareti
        $sheet->getColumnDimension('C')->setWidth(15);  // Mevcut durum

        // Gün başlıkları ve tablo başlıkları için stil
        $currentRow = 10;
        $gunSirasi = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma', 'cumartesi', 'pazar'];

        $zamanDilimleriByGun = [];
        foreach ($this->zamanDilimleri as $zd) {
            $zamanDilimleriByGun[$zd->haftanin_gunu][] = $zd;
        }

        foreach ($gunSirasi as $gun) {
            if (!isset($zamanDilimleriByGun[$gun]) || empty($zamanDilimleriByGun[$gun])) {
                continue;
            }

            // Gün başlığı stili
            $sheet->getStyle("A{$currentRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
            $sheet->mergeCells("A{$currentRow}:C{$currentRow}");
            $currentRow++;

            // Tablo başlığı stili
            $sheet->getStyle("A{$currentRow}:C{$currentRow}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9E2F3'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ]);
            $currentRow++;

            // Zaman dilimi satırları
            $gunZamanSayisi = count($zamanDilimleriByGun[$gun]);
            $endRow = $currentRow + $gunZamanSayisi - 1;

            $sheet->getStyle("A{$currentRow}:C{$endRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // X işaretli hücreleri kırmızı yap
            for ($i = $currentRow; $i <= $endRow; $i++) {
                if ($sheet->getCell("B{$i}")->getValue() === 'X') {
                    $sheet->getStyle("B{$i}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FF0000'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'FFE6E6'],
                        ],
                    ]);
                }
            }

            $currentRow = $endRow + 2; // Boş satır için +2
        }

        return [];
    }

    /**
     * Gerekli ders saatini hesapla
     */
    private function calculateRequiredHours(): int
    {
        $ogretmenDersler = OgretmenDers::where('ogretmen_id', $this->ogretmen->id)
            ->with(['ders'])
            ->get();

        $toplamSaat = 0;

        foreach ($ogretmenDersler as $ogretmenDers) {
            $ders = $ogretmenDers->ders;

            // Bu dersi kaç grup alıyor?
            $grupSayisi = \App\Models\GrupDers::where('ders_id', $ders->id)->count();

            // Toplam saat = ders haftalık saati × grup sayısı
            $toplamSaat += $ders->haftalik_saat * $grupSayisi;
        }

        return $toplamSaat;
    }
}
