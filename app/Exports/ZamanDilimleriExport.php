<?php

namespace App\Exports;

use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ZamanDilimleriExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * Veritabanındaki tüm zaman dilimlerini getir
     */
    public function collection()
    {
        return ZamanDilim::all();
    }

    /**
     * Her satır için veri haritalaması
     */
    public function map($zamanDilimi): array
    {
        return [
            $zamanDilimi->haftanin_gunu,
            // Sadece saat:dakika kısmını al
            substr($zamanDilimi->baslangic_saati, 0, 5),
            substr($zamanDilimi->bitis_saati, 0, 5),
        ];
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        return [
            'haftanin_gunu',
            'baslangic_saati',
            'bitis_saati',
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
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);

        return [];
    }
}
