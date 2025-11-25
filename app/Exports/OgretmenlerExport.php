<?php

namespace App\Exports;

use App\Models\Ogretmen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OgretmenlerExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * Veritabanındaki tüm öğretmenleri getir
     */
    public function collection()
    {
        return Ogretmen::all();
    }

    /**
     * Her satır için veri haritalaması
     */
    public function map($ogretmen): array
    {
        return [
            $ogretmen->isim,
            $ogretmen->unvan,
            $ogretmen->email,
        ];
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        return [
            'ad_soyad',
            'unvan',
            'e_posta',
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
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(35);

        return [];
    }
}
