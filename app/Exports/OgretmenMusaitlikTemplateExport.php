<?php

namespace App\Exports;

use App\Models\Ogretmen;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OgretmenMusaitlikTemplateExport implements FromArray, WithHeadings, WithStyles
{
    protected $ogretmenler;
    protected $zamanDilimleri;

    public function __construct()
    {
        $this->ogretmenler = Ogretmen::all();
        $this->zamanDilimleri = ZamanDilim::all()->sortBy([
            ['gun_sirasi', 'asc'],
            ['baslangic_saati', 'asc'],
        ])->values();
    }

    /**
     * Şablon verilerini döndür
     */
    public function array(): array
    {
        $data = [];

        foreach ($this->ogretmenler as $ogretmen) {
            $row = [$ogretmen->isim];

            // Her zaman dilimi için boş hücre (kullanıcı 1 veya 0 yazacak)
            foreach ($this->zamanDilimleri as $zd) {
                $row[] = ''; // Boş bırak, kullanıcı dolduracak
            }

            $data[] = $row;
        }

        return $data;
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        $headings = ['Öğretmen'];

        // Zaman dilimlerini başlık olarak ekle
        foreach ($this->zamanDilimleri as $zd) {
            $headings[] = ucfirst($zd->haftanin_gunu) . "\n" .
                         substr($zd->baslangic_saati, 0, 5) . '-' .
                         substr($zd->bitis_saati, 0, 5);
        }

        return $headings;
    }

    /**
     * Stil ayarları
     */
    public function styles(Worksheet $sheet)
    {
        $columnCount = count($this->zamanDilimleri) + 1;
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnCount);

        // Başlık satırını stillendir
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Başlık satırı yüksekliğini artır
        $sheet->getRowDimension(1)->setRowHeight(40);

        // Öğretmen sütunu genişliği
        $sheet->getColumnDimension('A')->setWidth(25);

        // Zaman dilimi sütunları genişliği
        for ($i = 2; $i <= $columnCount; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setWidth(12);
        }

        // Tüm hücreleri ortala
        $lastRow = count($this->ogretmenler) + 1;
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Açıklama ekle (en alta)
        $noteRow = $lastRow + 2;
        $sheet->setCellValue("A{$noteRow}", "NOT: Öğretmenin müsait olduğu saatlere '1', müsait olmadığı saatlere '0' yazın. Boş bırakılan hücreler '0' olarak kabul edilir.");
        $sheet->getStyle("A{$noteRow}")->getFont()->setItalic(true)->setSize(9);
        $sheet->mergeCells("A{$noteRow}:{$lastColumn}{$noteRow}");

        return [];
    }
}
