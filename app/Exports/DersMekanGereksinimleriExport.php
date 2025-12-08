<?php

namespace App\Exports;

use App\Models\DersMekanGereksinimi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DersMekanGereksinimleriExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * Veritabanındaki tüm ders mekan gereksinimlerini getir
     */
    public function collection()
    {
        return DersMekanGereksinimi::with('ders')->get();
    }

    /**
     * Her satır için veri haritalaması
     */
    public function map($mekanGereksinimi): array
    {
        return [
            $mekanGereksinimi->ders ? $mekanGereksinimi->ders->ders_kodu : '',
            $mekanGereksinimi->mekan_tipi,
            $mekanGereksinimi->gereksinim_tipi,
        ];
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        return [
            'ders_kodu',
            'mekan_tipi',
            'gereksinim_tipi'
        ];
    }

    /**
     * Stil ayarları
     */
    public function styles(Worksheet $sheet)
    {
        // Başlık satırını stillendir
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Kolon genişliklerini ayarla
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(18);

        return [];
    }
}
