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
                'Temel matematik konuları',
                4,
                'Teorik'
            ],
            [
                'FIZ101',
                'Fizik I',
                'Temel fizik konuları',
                3,
                'Teorik'
            ],
            [
                'BIL101',
                'Bilgisayar Programlama',
                'Temel programlama',
                5,
                'Uygulama'
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
            'aciklama',
            'haftalik_saat',
            'ders_tipi'
        ];
    }

    /**
     * Stil ayarları
     */
    public function styles(Worksheet $sheet)
    {
        // Başlık satırını stillendir
        $sheet->getStyle('A1:E1')->applyFromArray([
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
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        // Örnek satırları hafif gri yap
        $sheet->getStyle('A2:E4')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F0F0F0'],
            ],
        ]);

        return [];
    }
}
