<?php

namespace App\Http\Controllers;

use App\Models\Mekan;
use App\Exports\MekanlarTemplateExport;
use App\Imports\MekanlarImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

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
                        ->with('import_errors', $errorMessages);
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

    /**
     * Preview uploaded Excel rows and allow user to select which rows to import.
     */
    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:20480',
        ]);

        $file = $request->file('file');

        // store the file so user can confirm later without reupload
        $path = $file->store('imports');

        // Read excel to array (first sheet)
        $arrays = Excel::toArray(null, Storage::path($path));
        $rows = $arrays[0] ?? [];

        // If we have heading row, normalize keys to lowercase with underscores
        $previewRows = [];
        foreach ($rows as $index => $row) {
            // if row empty continue
            if (empty(array_filter($row))) continue;
            $previewRows[] = array_map(function ($v) {
                return is_null($v) ? '' : (string) $v;
            }, $row);
            if (count($previewRows) >= 200) break; // limit preview
        }

        return Inertia::render('Mekanlar/ImportPreview', [
            'rows' => $previewRows,
            'file' => $path,
        ]);
    }

    /**
     * Import only selected rows provided by preview.
     */
    public function importSelected(Request $request)
    {
        $request->validate([
            'file' => 'required|string',
            'selected' => 'required|array|min:1',
        ]);

        $path = $request->input('file');
        $selected = $request->input('selected');

        // load file
        $arrays = Excel::toArray(null, Storage::path($path));
        $rows = $arrays[0] ?? [];

        $imported = 0;
        $updated = 0;
        $errors = [];

        foreach ($selected as $rowIndex) {
            if (! isset($rows[$rowIndex])) continue;
            $row = $rows[$rowIndex];

            try {
                // Expecting columns: isim, kapasite, mekan_tipi or index positions
                $isim = $row[0] ?? null;
                $kapasite = isset($row[1]) && is_numeric($row[1]) ? (int) $row[1] : 0;
                $mekan_tipi = $row[2] ?? null;

                if (!$isim) {
                    $errors[] = "Satır #".($rowIndex+1) . ": isim boş";
                    continue;
                }

                $mekan = Mekan::updateOrCreate([
                    'isim' => $isim,
                ],[
                    'kapasite' => $kapasite,
                    'mekan_tipi' => $mekan_tipi,
                ]);

                if ($mekan->wasRecentlyCreated) $imported++; else $updated++;
            } catch (\Throwable $e) {
                $errors[] = "Satır #".($rowIndex+1) . ": " . $e->getMessage();
            }
        }

        // redirect back with stats
        return redirect()->route('mekanlar.index')
            ->with('success', "Seçilen satırlar içe aktarıldı. Eklenen: {$imported}, Güncellenen: {$updated}")
            ->with('import_errors', $errors);
    }
}
