<?php

namespace App\Exports;

use App\Models\OlusturulanProgram;
use App\Models\OgrenciGrubu;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TimetableExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $gruplar = OgrenciGrubu::all();

        foreach ($gruplar as $grup) {
            $sheets[] = new TimetablePerGroupSheet($grup);
        }

        return $sheets;
    }
}

class TimetablePerGroupSheet implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $grup;
    protected $zamanDilimleri;
    protected $gunler = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'];

    public function __construct(OgrenciGrubu $grup)
    {
        $this->grup = $grup;
        $this->zamanDilimleri = ZamanDilim::all()->sortBy([
            ['gun_sirasi', 'asc'],
            ['baslangic_saat', 'asc']
        ])->values();
    }

    public function title(): string
    {
        return substr($this->grup->isim, 0, 31); // Excel sheet name limit
    }

    public function collection()
    {
        $program = OlusturulanProgram::with(['ders', 'ogretmen', 'mekan'])
            ->where('ogrenci_grup_id', $this->grup->id)
            ->get();

        $rows = [];

        // Günlere göre grupla
        $gunlereGore = [];
        foreach ($this->zamanDilimleri as $zamanDilim) {
            if (!isset($gunlereGore[$zamanDilim->gun_sirasi])) {
                $gunlereGore[$zamanDilim->gun_sirasi] = [];
            }
            $gunlereGore[$zamanDilim->gun_sirasi][] = $zamanDilim;
        }

        foreach ($gunlereGore as $gunSirasi => $gunZamanDilimleri) {
            $gun = $this->gunler[$gunSirasi];

            foreach ($gunZamanDilimleri as $zamanDilim) {
                $ders = $program->where('zaman_dilimi_id', $zamanDilim->id)->first();

                $rows[] = [
                    'gun' => $gun,
                    'saat' => $zamanDilim->baslangic_saat . ' - ' . $zamanDilim->bitis_saat,
                    'ders' => $ders ? $ders->ders->isim : '-',
                    'ogretmen' => $ders ? $ders->ogretmen->isim : '-',
                    'mekan' => $ders ? $ders->mekan->isim : '-',
                ];
            }
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Gün',
            'Saat',
            'Ders',
            'Öğretmen',
            'Mekan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD'],
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }
}
