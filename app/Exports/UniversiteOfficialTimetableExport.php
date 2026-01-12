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

        // Başlık kısmı
        $data[] = ['KIRŞEHİR AHİ EVRAN ÜNİVERSİTESİ MÜHENDİSLİK-MİMARLIK FAKÜLTESİ'];
        $data[] = ["BİLGİSAYAR MÜHENDİSLİĞİ BÖLÜMÜ (TÜRKÇE) {$this->akademikYil} EĞİTİM-ÖĞRETİM YILI {$this->donem} DÖNEMİ HAFTALIK DERS PROGRAMI ({$this->subeType} ŞUBESİ)"];
        $data[] = [''];

        // Tablo başlıkları
        $headerRow1 = ['SAAT', 'GÜN'];
        $headerRow2 = ['', ''];

        // Her sınıf için başlık
        for ($sinif = 1; $sinif <= 4; $sinif++) {
            $headerRow1[] = "{$sinif}-{$this->subeType} SINIFI";
            $headerRow1[] = '';
            $headerRow1[] = '';

            $headerRow2[] = 'DERS';
            $headerRow2[] = 'HOCA';
            $headerRow2[] = 'YER';
        }

        $data[] = $headerRow1;
        $data[] = $headerRow2;

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

        $zamanByGun = [];
        foreach ($zamanDilimleri as $zd) {
            if (in_array($zd->haftanin_gunu, $gunSirasi)) {
                $zamanByGun[$zd->haftanin_gunu][] = $zd;
            }
        }

        // Her gün için satırlar oluştur
        foreach ($gunSirasi as $gun) {
            if (!isset($zamanByGun[$gun])) continue;

            $gunZamanlari = $zamanByGun[$gun];
            $gunBaslangic = true;

            foreach ($gunZamanlari as $zaman) {
                $row = [];

                // Saat sütunu
                $row[] = substr($zaman->baslangic_saati, 0, 5) . '-' . substr($zaman->bitis_saati, 0, 5);

                // Gün sütunu (sadece ilk satırda)
                $row[] = $gunBaslangic ? $gunIsimleri[$gun] : '';
                $gunBaslangic = false;

                // Her sınıf için ders bilgileri
                for ($sinif = 1; $sinif <= 4; $sinif++) {
                    $grupAdi = "{$sinif}-{$this->subeType}";
                    $grup = OgrenciGrubu::where('isim', $grupAdi)->first();

                    if ($grup) {
                        $program = OlusturulanProgram::where('ogrenci_grup_id', $grup->id)
                            ->where('zaman_dilimi_id', $zaman->id)
                            ->with(['ders', 'ogretmen', 'mekan'])
                            ->first();

                        if ($program) {
                            $row[] = $program->ders->isim ?? '';
                            $row[] = $this->formatOgretmenAdi($program->ogretmen->isim ?? '');
                            $row[] = $program->mekan->isim ?? '';
                        } else {
                            $row[] = '';
                            $row[] = '';
                            $row[] = '';
                        }
                    } else {
                        $row[] = '';
                        $row[] = '';
                        $row[] = '';
                    }
                }

                $data[] = $row;
            }
        }

        // Alt kısım
        $data[] = [''];
        $data[] = [''];
        $data[] = ['BÖLÜM BAŞKANI', '', '', '', '', '', '', '', '', '', '', '', '', 'DEKAN'];
        $data[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', ''];
        $data[] = ['Doç. Dr. Öğr. Üyesi Ahmet BİLİR', '', '', '', '', '', '', '', '', '', '', '', '', 'Prof. Dr. Cengiz ELDEM'];

        return $data;
    }

    /**
     * Öğretmen adını kısalt
     */
    private function formatOgretmenAdi($ad): string
    {
        if (strlen($ad) > 15) {
            $parcalar = explode(' ', $ad);
            if (count($parcalar) >= 2) {
                return $parcalar[0] . ' ' . substr($parcalar[count($parcalar) - 1], 0, 8);
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
            'A' => 12,  // Saat
            'B' => 12,  // Gün
            'C' => 20,  // 1-A Ders
            'D' => 15,  // 1-A Hoca
            'E' => 8,   // 1-A Yer
            'F' => 20,  // 2-A Ders
            'G' => 15,  // 2-A Hoca
            'H' => 8,   // 2-A Yer
            'I' => 20,  // 3-A Ders
            'J' => 15,  // 3-A Hoca
            'K' => 8,   // 3-A Yer
            'L' => 20,  // 4-A Ders
            'M' => 15,  // 4-A Hoca
            'N' => 8,   // 4-A Yer
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
        $sheet->getRowDimension(4)->setRowHeight(20);  // Tablo başlığı 1
        $sheet->getRowDimension(5)->setRowHeight(20);  // Tablo başlığı 2

        // Başlık stilleri
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->mergeCells('A1:N1');

        $sheet->getStyle('A2:N2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->mergeCells('A2:N2');

        // Tablo başlık stilleri
        $sheet->getStyle('A4:N5')->applyFromArray([
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

        // Sınıf başlıklarını merge et
        $sheet->mergeCells('C4:E4'); // 1-A
        $sheet->mergeCells('F4:H4'); // 2-A
        $sheet->mergeCells('I4:K4'); // 3-A
        $sheet->mergeCells('L4:N4'); // 4-A

        // Gün sütunlarını merge et (dinamik olarak)
        $currentRow = 6;
        $gunSirasi = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma'];

        foreach ($gunSirasi as $gun) {
            $zamanDilimleri = ZamanDilim::where('haftanin_gunu', $gun)
                ->orderBy('baslangic_saati')
                ->get();

            if ($zamanDilimleri->count() > 1) {
                $endRow = $currentRow + $zamanDilimleri->count() - 1;
                $sheet->mergeCells("B{$currentRow}:B{$endRow}");
            }

            $currentRow += $zamanDilimleri->count();
        }

        // Tablo içeriği stilleri
        $lastRow = $currentRow - 1;
        $sheet->getStyle("A6:N{$lastRow}")->applyFromArray([
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

        // Alt imza kısmı
        $signatureRow = $lastRow + 3;
        $sheet->getStyle("A{$signatureRow}:N{$signatureRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return [];
    }
}
