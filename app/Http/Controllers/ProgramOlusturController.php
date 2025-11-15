<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateTimetable;
use App\Services\TimetableGeneticAlgorithm;
use App\Models\OlusturulanProgram;
use App\Models\ZamanDilim;
use App\Models\OgrenciGrubu;
use App\Exports\TimetableExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $useBackgroundJob = $request->input('background', true);

        if ($useBackgroundJob) {
            // Background job olarak çalıştır
            GenerateTimetable::dispatch();

            return redirect()
                ->route('program-olustur.index')
                ->with('success', 'Program oluşturma işlemi arka planda başlatıldı. İlerlemeyi takip edebilirsiniz.');
        } else {
            // Senkron olarak çalıştır (eski yöntem)
            try {
                $ga = new TimetableGeneticAlgorithm();
                $result = $ga->generate();
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
    }

    /**
     * İlerleme durumunu getir
     */
    public function status()
    {
        $status = Cache::get('timetable_generation_status', [
            'status' => 'idle',
            'progress' => 0,
            'message' => 'Henüz başlatılmadı',
        ]);

        return response()->json($status);
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

    /**
     * Programı Excel olarak export et
     */
    public function exportExcel()
    {
        return Excel::download(new TimetableExport, 'ders-programi-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Programı PDF olarak export et
     */
    public function exportPdf()
    {
        $program = OlusturulanProgram::with([
            'ogrenciGrubu',
            'ders',
            'ogretmen',
            'zamanDilim',
            'mekan'
        ])->get();

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

        $pdf = Pdf::loadView('pdf.timetable', [
            'program_tablosu' => $programTablosu,
            'zaman_dilimleri' => $zamanDilimleri,
            'gruplar' => $gruplar,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('ders-programi-' . date('Y-m-d') . '.pdf');
    }
}
