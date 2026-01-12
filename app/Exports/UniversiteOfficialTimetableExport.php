<?php

namespace App\Exports;

use App\Models\OgrenciGrubu;
use App\Models\OlusturulanProgram;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UniversiteOfficialTimetableExport implements FromArray, WithStyles, WithColumnWidths
{
    protected $subeType; // 'A' veya 'B'
    protected $akademikYil;
    protected $donem;

    public function __construct($subeType = 'A', $akademikYil = '2024-2025', $donem = 'GÜZ')
    {
        $this->subeType = $subeType;
        $this->akademikYil = $akademikYil;
        $this->donem = $donem;
    }

    public function array(): array
    {
        $data = [];

        // Başlık kısmı - Okulun resmi şablonuna uygun
        $data[] = [
            'KIRŞEHİR AHİ EVRAN ÜNİVERSİTESİ MÜHENDİSLİK-MİMARLIK FAKÜLTESİ',
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
        ];
        $data[] = [
            "BİLGİSAYAR MÜHENDİSLİĞİ BÖLÜMÜ (TÜRKÇE) {$this->akademikYil} EĞİTİM-ÖĞRETİM YILI {$this->donem} DÖNEMİ HAFTALIK DERS PROGRAMI ({$this->subeType} ŞUBESİ)",
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''
        ];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        // Sınıf başlıkları
        $data[] = [
            '', '', 'I. SINIF', '', '', '', 'II. SINIF', '', '', '', 'III. SINIF', '', '', '', 'IV. SINIF', '', '', ''
        ];

        // Tablo başlıkları
        $data[] = [
            'GÜN', 'SAAT',
            'DERS KODU', 'DERS ADI', 'DERSLİK', 'ÖĞRETİM ELEMANI',
            'DERS KODU', 'DERS ADI', 'DERSLİK', 'ÖĞRETİM ELEMANI',
            'DERS KODU', 'DERS ADI', 'DERSLİK', 'ÖĞRETİM ELEMANI',
            'DERS KODU', 'DERS ADI', 'DERSLİK', 'ÖĞRETİM ELEMANI'
        ];

        // Zaman dilimlerini al ve sırala
        $zamanDilimleri = ZamanDilim::orderBy('baslangic_saati')->get();

        // Günlere göre grupla
        $gunSirasi = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma'];
        $gunIsimleri = [
            'pazartesi' => 'PAZARTESİ',
            'sali' => 'SALI',
            'carsamba' => 'ÇARŞAMBA',
            'persembe' => 'PERŞEMBE',
            'cuma' => 'CUMA'
        ];

        // Sabit saat dilimleri (okulun şablonuna uygun)
        $saatDilimleri = [
            '08:15 – 09:00',
            '09:15 – 10:00',
            '10:15 – 11:00',
            '11:15 – 12:00',
            '12:00 – 13:00',
            '13:00 – 13:45',
            '14:00 – 14:45',
            '15:00 – 15:45',
            '16:00 – 16:45'
        ];

        $zamanByGun = [];
        foreach ($zamanDilimleri as $zd) {
            if (in_array($zd->haftanin_gunu, $gunSirasi)) {
                $zamanByGun[$zd->haftanin_gunu][] = $zd;
            }
        }

        // Her gün için satırlar oluştur
        foreach ($gunSirasi as $gun) {
            $gunBaslangic = true;

            // Her gün için 9 saat dilimi (08:15-16:45 arası)
            for ($saatIndex = 0; $saatIndex < 9; $saatIndex++) {
                $row = [];

                // Gün sütunu (sadece ilk satırda)
                $row[] = $gunBaslangic ? $gunIsimleri[$gun] : '';

                // Saat sütunu
                $row[] = $saatDilimleri[$saatIndex];

                $gunBaslangic = false;

                // Mevcut zaman dilimini bul
                $mevcutZaman = null;
                if (isset($zamanByGun[$gun])) {
                    foreach ($zamanByGun[$gun] as $zaman) {
                        $zamanSaat = substr($zaman->baslangic_saati, 0, 5) . ' – ' . substr($zaman->bitis_saati, 0, 5);
                        if ($zamanSaat === $saatDilimleri[$saatIndex]) {
                            $mevcutZaman = $zaman;
                            break;
                        }
                    }
                }

                // Her sınıf için ders bilgileri (1-A, 2-A, 3-A, 4-A)
                for ($sinif = 1; $sinif <= 4; $sinif++) {
                    $grupAdi = "{$sinif}-{$this->subeType}";
                    $grup = OgrenciGrubu::where('isim', $grupAdi)->first();

                    if ($grup && $mevcutZaman) {
                        $program = OlusturulanProgram::where('ogrenci_grup_id', $grup->id)
                            ->where('zaman_dilimi_id', $mevcutZaman->id)
                            ->with(['ders', 'ogretmen', 'mekan'])
                            ->first();

                        if ($program) {
                            $row[] = $program->ders->ders_kodu ?? '';
                            $row[] = $program->ders->isim ?? '';
                            $row[] = $program->mekan->isim ?? '';
                            $row[] = $this->formatOgretmenAdi($program->ogretmen->isim ?? '');
                        } else {
                            $row[] = '';
                            $row[] = '';
                            $row[] = '';
                            $row[] = '';
                        }
                    } else {
                        $row[] = '';
                        $row[] = '';
                        $row[] = '';
                        $row[] = '';
                    }
                }

                $data[] = $row;
            }
        }

        // Alt boşluk
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''];

        return $data;
    }

    /**
     * Öğretmen adını kısalt
     */
    private function formatOgretmenAdi($ad): string
    {
        if (strlen($ad) > 20) {
            $parcalar = explode(' ', $ad);
            if (count($parcalar) >= 2) {
                return $parcalar[0] . ' ' . substr($parcalar[count($parcalar) - 1], 0, 10);
            }
        }
        return $ad;
    }

    /**
     * Sütun genişlikleri
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Gün
            'B' => 15,  // Saat
            'C' => 12,  // 1. Sınıf Ders Kodu
            'D' => 25,  // 1. Sınıf Ders Adı
            'E' => 10,  // 1. Sınıf Derslik
            'F' => 20,  // 1. Sınıf Öğretim Elemanı
            'G' => 12,  // 2. Sınıf Ders Kodu
            'H' => 25,  // 2. Sınıf Ders Adı
            'I' => 10,  // 2. Sınıf Derslik
            'J' => 20,  // 2. Sınıf Öğretim Elemanı
            'K' => 12,  // 3. Sınıf Ders Kodu
            'L' => 25,  // 3. Sınıf Ders Adı
            'M' => 10,  // 3. Sınıf Derslik
            'N' => 20,  // 3. Sınıf Öğretim Elemanı
            'O' => 12,  // 4. Sınıf Ders Kodu
            'P' => 25,  // 4. Sınıf Ders Adı
            'Q' => 10,  // 4. Sınıf Derslik
            'R' => 20,  // 4. Sınıf Öğretim Elemanı
        ];
    }

    /**
     * Stil ayarları
     */
    public function styles(Worksheet $sheet)
    {
        // Satır yüksekliklerini ayarla
        $sheet->getRowDimension(1)->setRowHeight(25);  // Başlık
        $sheet->getRowDimension(2)->setRowHeight(25);  // Alt başlık
        $sheet->getRowDimension(4)->setRowHeight(20);  // Sınıf başlıkları
        $sheet->getRowDimension(5)->setRowHeight(20);  // Tablo başlıkları

        // Ana başlık stilleri
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->mergeCells('A1:R1');

        $sheet->getStyle('A2:R2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->mergeCells('A2:R2');

        // Sınıf başlık stilleri
        $sheet->getStyle('A4:R4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Sınıf başlıklarını merge et
        $sheet->mergeCells('C4:F4'); // I. SINIF
        $sheet->mergeCells('G4:J4'); // II. SINIF
        $sheet->mergeCells('K4:N4'); // III. SINIF
        $sheet->mergeCells('O4:R4'); // IV. SINIF

        // Tablo başlık stilleri
        $sheet->getStyle('A5:R5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 9,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6E6FA'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Gün sütunlarını merge et
        $currentRow = 6;
        foreach (['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma'] as $gun) {
            $endRow = $currentRow + 8; // Her gün 9 saat dilimi
            $sheet->mergeCells("A{$currentRow}:A{$endRow}");
            $currentRow += 9;
        }

        // Tablo içeriği stilleri
        $lastRow = 50; // Yaklaşık son satır
        $sheet->getStyle("A6:R{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'font' => [
                'size' => 8,
            ],
        ]);

        return [];
    }
}
