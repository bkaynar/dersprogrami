<?php

namespace App\Exports;

use App\Models\Mekan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MekanlarExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * Veritabanındaki tüm mekanları getir
     */
    public function collection()
    {
        return Mekan::all();
    }

    /**
     * Her satır için veri haritalaması
     */
    public function map($mekan): array
    {
        return [
            $mekan->isim,
            $mekan->kapasite,
            $mekan->mekan_tipi,
        ];
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        return [
            'mekan_adi',
            'kapasite',
            'mekan_tipi',
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

        return [];
    }
}
