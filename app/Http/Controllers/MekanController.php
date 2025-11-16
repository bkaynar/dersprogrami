<?php

namespace App\Http\Controllers;

use App\Models\Mekan;
use App\Exports\MekanlarTemplateExport;
use App\Imports\MekanlarImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class MekanController extends Controller
{
    public function index()
    {
        $mekanlar = Mekan::orderBy('isim')->get(['id', 'isim', 'kapasite', 'mekan_tipi']);

        return Inertia::render('Mekanlar/Index', [
            'mekanlar' => $mekanlar,
        ]);
    }

    public function create()
    {
        return Inertia::render('Mekanlar/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'kapasite' => 'required|integer|min:1',
            'mekan_tipi' => 'nullable|string|max:100',
        ]);

        $mekan = Mekan::create($data);

        return redirect()->route('mekanlar.index')->with('success', 'Mekan oluşturuldu.');
    }

    public function show(Mekan $mekan)
    {
        return Inertia::render('Mekanlar/Show', [
            'mekan' => $mekan,
        ]);
    }

    public function edit(Mekan $mekan)
    {
        return Inertia::render('Mekanlar/Edit', [
            'mekan' => $mekan,
        ]);
    }

    public function update(Request $request, Mekan $mekan)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'kapasite' => 'required|integer|min:1',
            'mekan_tipi' => 'nullable|string|max:100',
        ]);

        $mekan->update($data);

        return redirect()->route('mekanlar.index')->with('success', 'Mekan güncellendi.');
    }

    public function destroy(Mekan $mekan)
    {
        $mekan->delete();

        return redirect()->route('mekanlar.index')->with('success', 'Mekan silindi.');
    }

    /**
     * Excel şablonunu indir
     */
    public function downloadTemplate()
    {
        return Excel::download(new MekanlarTemplateExport, 'mekanlar-sablonu.xlsx');
    }

    /**
     * Excel'den toplu veri yükle
     */
    public function import(Request $request)
    {
        \Log::info('Import method called', [
            'has_file' => $request->hasFile('file'),
            'files' => $request->allFiles(),
            'all_data' => $request->all()
        ]);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            \Log::info('Starting import process');
            $import = new MekanlarImport();

            \Log::info('Importing Excel file');
            Excel::import($import, $request->file('file'));

            \Log::info('Getting stats');
            $stats = $import->getStats();
            $failures = $import->getFailures();
            $errors = $import->getErrors();

            \Log::info('Import stats', [
                'stats' => $stats,
                'failures_count' => count($failures),
                'errors_count' => count($errors)
            ]);

            // Hata varsa detayları göster
            if (count($failures) > 0 || count($errors) > 0) {
                $errorMessages = [];

                foreach ($failures as $failure) {
                    $errorMessages[] = "Satır {$failure['row']}: " . implode(', ', $failure['errors']);
                }

                foreach ($errors as $error) {
                    $errorMessages[] = $error;
                }

                return redirect()
                    ->route('mekanlar.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}, Hata: " . count($errorMessages))
                    ->with('errors', $errorMessages);
            }

            return redirect()
                ->route('mekanlar.index')
                ->with('success', "Excel başarıyla yüklendi! Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}");

        } catch (\Exception $e) {
            \Log::error('Import exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('mekanlar.index')
                ->with('error', 'Excel yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }
}
