<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateTimetable;
use App\Services\TimetableGeneticAlgorithm;
use App\Models\OlusturulanProgram;
use App\Models\ZamanDilim;
use App\Models\OgrenciGrubu;
use App\Exports\TimetableExport;
use App\Exports\UniversiteOfficialTimetableExport;
use App\Exports\UniversiteOfficialTimetablePdfExport;
use App\Exports\UniversiteTemplateOverlayExport;
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

        // Eksik verileri kontrol et
        $missingData = $this->getMissingData();

        // Job durumunu kontrol et
        $jobStatus = $this->getJobStatus();

        return Inertia::render('ProgramOlustur/Index', [
            'mevcut_program' => $mevcutProgram,
            'program_var' => $mevcutProgram->count() > 0,
            'missing_data' => $missingData,
            'can_generate' => empty($missingData),
            'job_status' => $jobStatus, // Job durumunu frontend'e gönder
        ]);
    }

    /**
     * Genetik algoritma ile yeni program oluştur
     */
    public function generate(Request $request)
    {
        // Önce gerekli verilerin olup olmadığını kontrol et
        $validation = $this->validateRequiredData();
        if ($validation !== true) {
            return redirect()
                ->route('program-olustur.index')
                ->with('error', $validation);
        }

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
     * Eksik verileri getir (frontend için)
     */
    private function getMissingData()
    {
        $checks = [
            ['model' => \App\Models\OgrenciGrubu::class, 'name' => 'Öğrenci Grubu', 'route' => 'ogrenci-gruplari'],
            ['model' => \App\Models\Ders::class, 'name' => 'Ders', 'route' => 'dersler'],
            ['model' => \App\Models\Ogretmen::class, 'name' => 'Öğretmen', 'route' => 'ogretmenler'],
            ['model' => \App\Models\Mekan::class, 'name' => 'Mekan', 'route' => 'mekanlar'],
            ['model' => \App\Models\ZamanDilim::class, 'name' => 'Zaman Dilimi', 'route' => 'zaman-dilimleri'],
            ['model' => \App\Models\GrupDers::class, 'name' => 'Grup Dersleri (Hangi grubun hangi dersi aldığı)', 'route' => 'grup-dersleri'],
            ['model' => \App\Models\OgretmenDers::class, 'name' => 'Öğretmen Dersleri (Hangi öğretmenin hangi dersi verdiği)', 'route' => 'ogretmen-dersleri'],
        ];

        $missingData = [];
        foreach ($checks as $check) {
            if ($check['model']::count() === 0) {
                $missingData[] = [
                    'name' => $check['name'],
                    'route' => $check['route'],
                ];
            }
        }

        return $missingData;
    }

    /**
     * Program oluşturmak için gerekli verilerin kontrolü
     */
    private function validateRequiredData()
    {
        $missingData = $this->getMissingData();

        if (!empty($missingData)) {
            $names = array_column($missingData, 'name');
            return 'Program oluşturmak için eksik veriler var: ' . implode(', ', $names) . '. Lütfen önce bu verileri ekleyin.';
        }

        return true;
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
     * Job durumunu getir (internal kullanım için)
     */
    private function getJobStatus()
    {
        return Cache::get('timetable_generation_status', [
            'status' => 'idle',
            'progress' => 0,
            'message' => 'Henüz başlatılmadı',
        ]);
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
        $zamanDilimleri = ZamanDilim::all()->sortBy([
            ['gun_sirasi', 'asc'],
            ['baslangic_saat', 'asc']
        ])->values();
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
                    ->where('zaman_dilimi_id', $zamanDilim->id)
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

        $zamanDilimleri = ZamanDilim::all()->sortBy([
            ['gun_sirasi', 'asc'],
            ['baslangic_saat', 'asc']
        ])->values();
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
                    ->where('zaman_dilimi_id', $zamanDilim->id)
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

    /**
     * Üniversite resmi şablonunda Excel export (A şubesi)
     */
    public function exportUniversiteExcelA()
    {
        return Excel::download(
            new UniversiteOfficialTimetableExport('A'),
            'ders-programi-a-subesi-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Üniversite resmi şablonunda Excel export (B şubesi)
     */
    public function exportUniversiteExcelB()
    {
        return Excel::download(
            new UniversiteOfficialTimetableExport('B'),
            'ders-programi-b-subesi-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Üniversite resmi şablonunda PDF export (A şubesi)
     */
    public function exportUniversitePdfA()
    {
        $pdfExport = new UniversiteOfficialTimetablePdfExport('A');
        $pdf = $pdfExport->generate();

        return $pdf->download('ders-programi-a-subesi-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Üniversite resmi şablonunda PDF export (B şubesi)
     */
    public function exportUniversitePdfB()
    {
        $pdfExport = new UniversiteOfficialTimetablePdfExport('B');
        $pdf = $pdfExport->generate();

        return $pdf->download('ders-programi-b-subesi-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Üniversite template overlay export (A şubesi) - Okulun orijinal şablonu üzerine veri yazar
     */
    public function exportUniversiteTemplateA()
    {
        return Excel::download(
            new UniversiteTemplateOverlayExport('A'),
            'ders-programi-template-a-subesi-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Üniversite template overlay export (B şubesi) - Okulun orijinal şablonu üzerine veri yazar
     */
    public function exportUniversiteTemplateB()
    {
        return Excel::download(
            new UniversiteTemplateOverlayExport('B'),
            'ders-programi-template-b-subesi-' . date('Y-m-d') . '.xlsx'
        );
    }
}
