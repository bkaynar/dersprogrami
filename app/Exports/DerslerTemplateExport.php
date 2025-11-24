<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DerslerTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * Boş şablon döndür (örnek satırlar ile)
     */
    public function collection()
    {
        return collect([
            [
                'MAT101',
                'Matematik I',
                4,
            ],
            [
                'FIZ101',
                'Fizik I',
                3,
            ],
            [
                'BIL101',
                'Bilgisayar Programlama',
                5,
            ],
        ]);
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        return [
            'ders_kodu',
            'ders_adi',
            'haftalik_saat',
        ];
    }

    /**
     * Stil ayarları
     */
    public function styles(Worksheet $sheet)
    {
        // Başlık satırını stillendir
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Kolon genişliklerini ayarla
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);

        // Örnek satırları hafif gri yap
        $sheet->getStyle('A2:C4')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F0F0F0'],
            ],
        ]);

        return [];
    }
}
