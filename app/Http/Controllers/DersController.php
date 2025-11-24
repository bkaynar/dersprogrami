<?php

namespace App\Http\Controllers;

use App\Models\Ders;
use App\Exports\DerslerTemplateExport;
use App\Imports\DerslerImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class DersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $dersler = Ders::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ders_kodu', 'like', "%{$search}%")
                      ->orWhere('isim', 'like', "%{$search}%");
                });
            })
            ->orderBy('isim')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Dersler/Index', [
            'dersler' => $dersler,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Dersler/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ders_kodu' => 'required|string|max:50|unique:dersler,ders_kodu',
            'isim' => 'required|string|max:255',
            'haftalik_saat' => 'required|integer|min:0',
        ]);

        $ders = Ders::create($data);

        return redirect()->route('dersler.index')->with('success', 'Ders oluşturuldu.');
    }

    public function show(Ders $ders)
    {
        return Inertia::render('Dersler/Show', [
            'ders' => $ders,
        ]);
    }

    public function edit(Ders $ders)
    {
        return Inertia::render('Dersler/Edit', [
            'ders' => $ders,
        ]);
    }

    public function update(Request $request, Ders $ders)
    {
        $data = $request->validate([
            'ders_kodu' => 'required|string|max:50|unique:dersler,ders_kodu,' . $ders->id,
            'isim' => 'required|string|max:255',
            'haftalik_saat' => 'required|integer|min:0',
        ]);

        $ders->update($data);

        return redirect()->route('dersler.index')->with('success', 'Ders güncellendi.');
    }

    public function destroy(Ders $ders)
    {
        $ders->delete();

        return redirect()->route('dersler.index')->with('success', 'Ders silindi.');
    }

    /**
     * Excel şablonunu indir
     */
    public function downloadTemplate()
    {
        return Excel::download(new DerslerTemplateExport, 'dersler-sablonu.xlsx');
    }

    /**
     * Excel'den toplu veri yükle
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            \Log::info('Dersler import başladı', [
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_size' => $request->file('file')->getSize(),
            ]);

            $import = new DerslerImport();
            Excel::import($import, $request->file('file'));

            $stats = $import->getStats();
            $failures = $import->getFailures();
            $errors = $import->getErrors();

            \Log::info('Dersler import tamamlandı', [
                'stats' => $stats,
                'failures_count' => count($failures),
                'errors_count' => count($errors),
            ]);

            // Hata varsa detayları göster
            if (count($failures) > 0 || count($errors) > 0) {
                $errorMessages = [];

                foreach ($failures as $failure) {
                    $errorMsg = "Satır {$failure['row']}: " . implode(', ', $failure['errors']);
                    $errorMessages[] = $errorMsg;
                    \Log::warning('Import validation hatası', [
                        'row' => $failure['row'],
                        'errors' => $failure['errors'],
                        'values' => $failure['values'],
                    ]);
                }

                foreach ($errors as $error) {
                    $errorMessages[] = $error;
                    \Log::error('Import genel hatası', ['error' => $error]);
                }

                return redirect()
                    ->route('dersler.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}, Hata: " . count($errorMessages))
                    ->with('import_errors', $errorMessages);
            }

            return redirect()
                ->route('dersler.index')
                ->with('success', "Excel başarıyla yüklendi! Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}");

        } catch (\Exception $e) {
            \Log::error('Dersler import exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('dersler.index')
                ->with('error', 'Excel yüklenirken hata oluştu: ' . $e->getMessage() . ' (Satır: ' . $e->getLine() . ')');
        }
    }
}
