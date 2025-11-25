<?php

namespace App\Exports;

use App\Models\OgrenciGrubu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OgrenciGruplariExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    /**
     * Veritabanındaki tüm öğrenci gruplarını getir
     */
    public function collection()
    {
        return OgrenciGrubu::with('ustGrup')->get();
    }

    /**
     * Her satır için veri haritalaması
     */
    public function map($ogrenciGrubu): array
    {
        return [
            $ogrenciGrubu->isim,
            $ogrenciGrubu->yil,
            $ogrenciGrubu->ogrenci_sayisi,
            $ogrenciGrubu->ustGrup ? $ogrenciGrubu->ustGrup->isim : null,
        ];
    }

    /**
     * Excel başlıkları
     */
    public function headings(): array
    {
        return [
            'isim',
            'yil',
            'ogrenci_sayisi',
            'ust_grup_adi',
        ];
    }

    /**
     * Stil ayarları
     */
    public function styles(Worksheet $sheet)
    {
        // Başlık satırını stillendir
        $sheet->getStyle('A1:D1')->applyFromArray([
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
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(30);

        return [];
    }
}
