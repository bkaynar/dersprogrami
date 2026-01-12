<?php

namespace App\Exports;

use App\Models\OgrenciGrubu;
use App\Models\OlusturulanProgram;
use App\Models\ZamanDilim;
use Barryvdh\DomPDF\Facade\Pdf;

class UniversiteOfficialTimetablePdfExport
{
    protected $subeType;
    protected $akademikYil;
    protected $donem;

    public function __construct($subeType = 'A', $akademikYil = '2024-2025', $donem = 'GÜZ')
    {
        $this->subeType = $subeType;
        $this->akademikYil = $akademikYil;
        $this->donem = $donem;
    }

    public function generate()
    {
        $data = $this->prepareData();

        $html = view('exports.universite-timetable-pdf', $data)->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');

        return $pdf;
    }

    private function prepareData()
    {
        // Zaman dilimlerini al ve sırala
        $zamanDilimleri = ZamanDilim::orderBy('baslangic_saati')->get();

        // Günlere göre grupla
        $gunSirasi = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma'];
        $gunIsimleri = [
            'pazartesi' => 'PAZARTESİ',
            'sali' => 'SALI',
            'carsamba' => 'ÇARŞAMBA',
            'persembe' => 'PERŞEMBE',
            'cuma' => 'CUMA'
        ];

        $zamanByGun = [];
        foreach ($zamanDilimleri as $zd) {
            if (in_array($zd->haftanin_gunu, $gunSirasi)) {
                $zamanByGun[$zd->haftanin_gunu][] = $zd;
            }
        }

        // Program verilerini hazırla
        $programData = [];
        foreach ($gunSirasi as $gun) {
            if (!isset($zamanByGun[$gun])) continue;

            $gunZamanlari = $zamanByGun[$gun];

            foreach ($gunZamanlari as $index => $zaman) {
                $row = [
                    'saat' => substr($zaman->baslangic_saati, 0, 5) . '-' . substr($zaman->bitis_saati, 0, 5),
                    'gun' => $index === 0 ? $gunIsimleri[$gun] : '',
                    'gun_rowspan' => $index === 0 ? count($gunZamanlari) : 0,
                    'siniflar' => []
                ];

                // Her sınıf için ders bilgileri
                for ($sinif = 1; $sinif <= 4; $sinif++) {
                    $grupAdi = "{$sinif}-{$this->subeType}";
                    $grup = OgrenciGrubu::where('isim', $grupAdi)->first();

                    if ($grup) {
                        $program = OlusturulanProgram::where('ogrenci_grup_id', $grup->id)
                            ->where('zaman_dilimi_id', $zaman->id)
                            ->with(['ders', 'ogretmen', 'mekan'])
                            ->first();

                        if ($program) {
                            $row['siniflar'][$sinif] = [
                                'ders' => $program->ders->isim ?? '',
                                'hoca' => $this->formatOgretmenAdi($program->ogretmen->isim ?? ''),
                                'yer' => $program->mekan->isim ?? ''
                            ];
                        } else {
                            $row['siniflar'][$sinif] = [
                                'ders' => '',
                                'hoca' => '',
                                'yer' => ''
                            ];
                        }
                    } else {
                        $row['siniflar'][$sinif] = [
                            'ders' => '',
                            'hoca' => '',
                            'yer' => ''
                        ];
                    }
                }

                $programData[] = $row;
            }
        }

        return [
            'subeType' => $this->subeType,
            'akademikYil' => $this->akademikYil,
            'donem' => $this->donem,
            'programData' => $programData,
            'tarih' => now()->format('d.m.Y')
        ];
    }

    /**
     * Öğretmen adını kısalt
     */
    private function formatOgretmenAdi($ad): string
    {
        if (strlen($ad) > 15) {
            $parcalar = explode(' ', $ad);
            if (count($parcalar) >= 2) {
                return $parcalar[0] . ' ' . substr($parcalar[count($parcalar) - 1], 0, 8);
            }
        }
        return $ad;
    }
}
