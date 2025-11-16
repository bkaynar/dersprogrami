<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MekanlarTemplateExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * Boş şablon döndür (örnek satırlar ile)
     */
    public function collection()
    {
        return collect([
            [
                'Amfi A1',
                200,
                'Amfi'
            ],
            [
                'Bilgisayar Lab 1',
                40,
                'Laboratuvar'
            ],
            [
                'Derslik 101',
                60,
                'Derslik'
            ],
            [
                'Konferans Salonu',
                300,
                'Salon'
            ],
        ]);
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        return [
            'mekan_adi',
            'kapasite',
            'mekan_tipi'
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
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);

        // Örnek satırları hafif gri yap
        $sheet->getStyle('A2:C5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F0F0F0'],
            ],
        ]);

        return [];
    }
}
