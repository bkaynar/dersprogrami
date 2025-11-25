<?php

namespace App\Exports;

use App\Models\Ders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DerslerExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * Veritabanındaki tüm dersleri getir
     */
    public function collection()
    {
        return Ders::all();
    }

    /**
     * Her satır için veri haritalaması
     */
    public function map($ders): array
    {
        return [
            $ders->ders_kodu,
            $ders->isim,
            $ders->haftalik_saat,
        ];
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

        return [];
    }
}
