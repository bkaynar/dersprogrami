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

        // Sabit saat dilimleri (okulun şablonuna uygun)
        $saatDilimleri = [
            '08:15 – 09:00',
            '09:15 – 10:00',
            '10:15 – 11:00',
            '11:15 – 12:00',
            '12:00 – 13:00',
            '13:00 – 13:45',
            '14:00 – 14:45',
            '15:00 – 15:45',
            '16:00 – 16:45'
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
            for ($saatIndex = 0; $saatIndex < 9; $saatIndex++) {
                // Mevcut zaman dilimini bul
                $mevcutZaman = null;
                if (isset($zamanByGun[$gun])) {
                    foreach ($zamanByGun[$gun] as $zaman) {
                        $zamanSaat = substr($zaman->baslangic_saati, 0, 5) . ' – ' . substr($zaman->bitis_saati, 0, 5);
                        if ($zamanSaat === $saatDilimleri[$saatIndex]) {
                            $mevcutZaman = $zaman;
                            break;
                        }
                    }
                }

                $row = [
                    'saat' => $saatDilimleri[$saatIndex],
                    'gun' => $saatIndex === 0 ? $gunIsimleri[$gun] : '',
                    'gun_rowspan' => $saatIndex === 0 ? 9 : 0,
                    'siniflar' => []
                ];

                // Her sınıf için ders bilgileri
                for ($sinif = 1; $sinif <= 4; $sinif++) {
                    $grupAdi = "{$sinif}-{$this->subeType}";
                    $grup = OgrenciGrubu::where('isim', $grupAdi)->first();

                    if ($grup && $mevcutZaman) {
                        $program = OlusturulanProgram::where('ogrenci_grup_id', $grup->id)
                            ->where('zaman_dilimi_id', $mevcutZaman->id)
                            ->with(['ders', 'ogretmen', 'mekan'])
                            ->first();

                        if ($program) {
                            $row['siniflar'][$sinif] = [
                                'ders_kodu' => $program->ders->ders_kodu ?? '',
                                'ders_adi' => $program->ders->isim ?? '',
                                'derslik' => $program->mekan->isim ?? '',
                                'ogretim_elemani' => $this->formatOgretmenAdi($program->ogretmen->isim ?? '')
                            ];
                        } else {
                            $row['siniflar'][$sinif] = [
                                'ders_kodu' => '',
                                'ders_adi' => '',
                                'derslik' => '',
                                'ogretim_elemani' => ''
                            ];
                        }
                    } else {
                        $row['siniflar'][$sinif] = [
                            'ders_kodu' => '',
                            'ders_adi' => '',
                            'derslik' => '',
                            'ogretim_elemani' => ''
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
        if (strlen($ad) > 20) {
            $parcalar = explode(' ', $ad);
            if (count($parcalar) >= 2) {
                return $parcalar[0] . ' ' . substr($parcalar[count($parcalar) - 1], 0, 10);
            }
        }
        return $ad;
    }
}
