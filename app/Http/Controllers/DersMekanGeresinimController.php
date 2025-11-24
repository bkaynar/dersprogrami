<?php

namespace App\Http\Controllers;

use App\Models\Ders;
use App\Models\DersMekanGereksinimi;
use App\Imports\DersMekanGereksinimleriImport;
use App\Exports\DersMekanGereksinimleriTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DersMekanGeresinimController extends Controller
{
    public function index()
    {
        $gereksinimler = DersMekanGereksinimi::with('ders')
            ->get();

        return Inertia::render('DersMekanGereksinimleri/Index', [
            'gereksinimler' => $gereksinimler,
        ]);
    }

    public function create()
    {
        // Tüm dersleri al
        $dersler = Ders::orderBy('isim')->get(['id', 'ders_kodu', 'isim']);

        // Zaten eklenmiş ders ID'lerini al
        $eklenmisDersIds = DersMekanGereksinimi::pluck('ders_id')->unique()->toArray();

        return Inertia::render('DersMekanGereksinimleri/Create', [
            'dersler' => $dersler,
            'eklenmis_ders_ids' => $eklenmisDersIds,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ders_id' => 'required|exists:dersler,id',
            'mekan_tipi' => 'required|in:derslik,laboratuvar,konferans_salonu',
            'gereksinim_tipi' => 'required|in:zorunlu,olabilir',
        ]);

        DersMekanGereksinimi::create($data);

        return redirect()->route('ders-mekan-gereksinimleri.index')
            ->with('success', 'Mekan gereksinimi oluşturuldu.');
    }

    public function show(DersMekanGereksinimi $dersMekanGereksinimi)
    {
        $dersMekanGereksinimi->load('ders');

        return Inertia::render('DersMekanGereksinimleri/Show', [
            'gereksinim' => $dersMekanGereksinimi,
        ]);
    }

    public function edit(DersMekanGereksinimi $dersMekanGereksinimi)
    {
        // Tüm dersleri al
        $dersler = Ders::orderBy('isim')->get(['id', 'ders_kodu', 'isim']);

        // Zaten eklenmiş ders ID'lerini al (düzenlenen ders hariç)
        $eklenmisDersIds = DersMekanGereksinimi::where('id', '!=', $dersMekanGereksinimi->id)
            ->pluck('ders_id')
            ->unique()
            ->toArray();

        return Inertia::render('DersMekanGereksinimleri/Edit', [
            'gereksinim' => $dersMekanGereksinimi->load('ders'),
            'dersler' => $dersler,
            'eklenmis_ders_ids' => $eklenmisDersIds,
        ]);
    }

    public function update(Request $request, DersMekanGereksinimi $dersMekanGereksinimi)
    {
        $data = $request->validate([
            'ders_id' => 'required|exists:dersler,id',
            'mekan_tipi' => 'required|in:derslik,laboratuvar,konferans_salonu',
            'gereksinim_tipi' => 'required|in:zorunlu,olabilir',
        ]);

        $dersMekanGereksinimi->update($data);

        return redirect()->route('ders-mekan-gereksinimleri.index')
            ->with('success', 'Mekan gereksinimi güncellendi.');
    }

    public function destroy(DersMekanGereksinimi $dersMekanGereksinimi)
    {
        $dersMekanGereksinimi->delete();

        return redirect()->route('ders-mekan-gereksinimleri.index')
            ->with('success', 'Mekan gereksinimi silindi.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new DersMekanGereksinimleriTemplateExport, 'ders-mekan-gereksinimleri-sablonu.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'create_missing' => 'nullable|boolean',
        ]);
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $import = new DersMekanGereksinimleriImport($request->boolean('create_missing'));
            Excel::import($import, $request->file('file'));

            $stats = $import->getStats();
            $failures = $import->getFailures();
            $errors = $import->getErrors();

            if (count($failures) > 0 || count($errors) > 0) {
                $errorMessages = [];

                foreach ($failures as $failure) {
                    $errorMessages[] = "Satır {$failure['row']}: " . implode(', ', $failure['errors']);
                }

                foreach ($errors as $error) {
                    $errorMessages[] = $error;
                }

                return redirect()
                    ->route('ders-mekan-gereksinimleri.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}, Hata: " . count($errorMessages))
                    ->with('import_errors', $errorMessages);
            }

            return redirect()
                ->route('ders-mekan-gereksinimleri.index')
                ->with('success', "Excel başarıyla yüklendi! Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}");

        } catch (\Exception $e) {
            return redirect()
                ->route('ders-mekan-gereksinimleri.index')
                ->with('error', 'Excel yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }
}
