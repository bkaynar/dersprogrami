<?php

namespace App\Http\Controllers;

use App\Models\Ogretmen;
use App\Exports\OgretmenlerTemplateExport;
use App\Imports\OgretmenlerImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class OgretmenController extends Controller
{
    public function index()
    {
        $ogretmenler = Ogretmen::orderBy('isim')->get(['id', 'isim', 'unvan', 'email']);

        return Inertia::render('Ogretmenler/Index', [
            'ogretmenler' => $ogretmenler,
        ]);
    }

    public function create()
    {
        return Inertia::render('Ogretmenler/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'unvan' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:ogretmenler,email',
        ]);

        $ogretmen = Ogretmen::create($data);

        return redirect()->route('ogretmenler.index')->with('success', 'Öğretmen oluşturuldu.');
    }

    public function show(Ogretmen $ogretmen)
    {
        return Inertia::render('Ogretmenler/Show', [
            'ogretmen' => $ogretmen,
        ]);
    }

    public function edit(Ogretmen $ogretmen)
    {
        return Inertia::render('Ogretmenler/Edit', [
            'ogretmen' => $ogretmen,
        ]);
    }

    public function update(Request $request, Ogretmen $ogretmen)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'unvan' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:ogretmenler,email,' . $ogretmen->id,
        ]);

        $ogretmen->update($data);

        return redirect()->route('ogretmenler.index')->with('success', 'Öğretmen güncellendi.');
    }

    public function destroy(Ogretmen $ogretmen)
    {
        $ogretmen->delete();

        return redirect()->route('ogretmenler.index')->with('success', 'Öğretmen silindi.');
    }

    /**
     * Excel şablonunu indir
     */
    public function downloadTemplate()
    {
        return Excel::download(new OgretmenlerTemplateExport, 'ogretmenler-sablonu.xlsx');
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
            $import = new OgretmenlerImport();
            Excel::import($import, $request->file('file'));

            $stats = $import->getStats();
            $failures = $import->getFailures();
            $errors = $import->getErrors();

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
                    ->route('ogretmenler.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}, Hata: " . count($errorMessages))
                    ->with('import_errors', $errorMessages);
            }

            return redirect()
                ->route('ogretmenler.index')
                ->with('success', "Excel başarıyla yüklendi! Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}");

        } catch (\Exception $e) {
            return redirect()
                ->route('ogretmenler.index')
                ->with('error', 'Excel yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }
}
