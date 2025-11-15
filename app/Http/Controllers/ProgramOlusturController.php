<?php

namespace App\Http\Controllers;

use App\Services\TimetableGeneticAlgorithm;
use App\Models\OlusturulanProgram;
use App\Models\ZamanDilim;
use App\Models\OgrenciGrubu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramOlusturController extends Controller
{
    /**
     * Program oluşturma sayfasını göster
     */
    public function index()
    {
        $mevcutProgram = OlusturulanProgram::with([
            'ogrenciGrubu',
            'ders',
            'ogretmen',
            'zamanDilim',
            'mekan'
        ])->get();

        return Inertia::render('ProgramOlustur/Index', [
            'mevcut_program' => $mevcutProgram,
            'program_var' => $mevcutProgram->count() > 0,
        ]);
    }

    /**
     * Genetik algoritma ile yeni program oluştur
     */
    public function generate(Request $request)
    {
        try {
            $ga = new TimetableGeneticAlgorithm();

            // Programı oluştur
            $result = $ga->generate();

            // Veritabanına kaydet
            $ga->saveSchedule($result['schedule']);

            return redirect()
                ->route('program-olustur.index')
                ->with('success', "Program başarıyla oluşturuldu! (Fitness: {$result['fitness']}, Nesil: {$result['generations']})");
        } catch (\Exception $e) {
            return redirect()
                ->route('program-olustur.index')
                ->with('error', 'Program oluşturulurken hata: ' . $e->getMessage());
        }
    }

    /**
     * Programı görüntüle
     */
    public function show()
    {
        $program = OlusturulanProgram::with([
            'ogrenciGrubu',
            'ders',
            'ogretmen',
            'zamanDilim',
            'mekan'
        ])->get();

        if ($program->isEmpty()) {
            return redirect()
                ->route('program-olustur.index')
                ->with('error', 'Henüz oluşturulmuş bir program yok.');
        }

        // Zaman dilimlerini ve grupları al
        $zamanDilimleri = ZamanDilim::orderBy('gun_sirasi')->orderBy('baslangic_saat')->get();
        $gruplar = OgrenciGrubu::all();

        // Programı tablo formatına dönüştür
        $programTablosu = [];
        foreach ($gruplar as $grup) {
            $programTablosu[$grup->id] = [
                'grup' => $grup,
                'dersler' => []
            ];

            foreach ($zamanDilimleri as $zamanDilim) {
                $ders = $program
                    ->where('ogrenci_grup_id', $grup->id)
                    ->where('zaman_dilim_id', $zamanDilim->id)
                    ->first();

                $programTablosu[$grup->id]['dersler'][$zamanDilim->id] = $ders;
            }
        }

        return Inertia::render('ProgramOlustur/Show', [
            'program' => $program,
            'program_tablosu' => $programTablosu,
            'zaman_dilimleri' => $zamanDilimleri,
            'gruplar' => $gruplar,
        ]);
    }

    /**
     * Programı sil
     */
    public function destroy()
    {
        OlusturulanProgram::truncate();

        return redirect()
            ->route('program-olustur.index')
            ->with('success', 'Program silindi.');
    }
}
