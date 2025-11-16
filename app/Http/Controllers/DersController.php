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
    public function index()
    {
        $dersler = Ders::orderBy('isim')->get(['id', 'ders_kodu', 'isim', 'haftalik_saat']);

        return Inertia::render('Dersler/Index', [
            'dersler' => $dersler,
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
            $import = new DerslerImport();
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
                    ->route('dersler.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}, Hata: " . count($errorMessages))
                    ->with('errors', $errorMessages);
            }

            return redirect()
                ->route('dersler.index')
                ->with('success', "Excel başarıyla yüklendi! Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}");

        } catch (\Exception $e) {
            return redirect()
                ->route('dersler.index')
                ->with('error', 'Excel yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }
}
