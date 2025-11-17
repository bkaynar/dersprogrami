<?php

namespace App\Http\Controllers;

use App\Models\OgrenciGrubu;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Imports\OgrenciGruplariImport;
use App\Exports\OgrenciGruplariTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class OgrenciGrubuController extends Controller
{
    public function index()
    {
        $grublar = OgrenciGrubu::with('ustGrup')
            ->orderBy('yil')
            ->orderBy('isim')
            ->get(['id', 'isim', 'yil', 'ogrenci_sayisi', 'ust_grup_id']);

        return Inertia::render('OgrenciGruplari/Index', [
            'ogrenci_gruplari' => $grublar,
        ]);
    }

    public function create()
    {
        $ustGruplar = OgrenciGrubu::orderBy('isim')->get(['id', 'isim']);

        return Inertia::render('OgrenciGruplari/Create', [
            'ustGruplar' => $ustGruplar,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'yil' => 'required|integer|min:1|max:6',
            'ogrenci_sayisi' => 'required|integer|min:1',
            'ust_grup_id' => 'nullable|exists:ogrenci_gruplari,id',
        ]);

        $grup = OgrenciGrubu::create($data);

        return redirect()->route('ogrenci-gruplari.index')->with('success', 'Öğrenci grubu oluşturuldu.');
    }

    public function show(OgrenciGrubu $ogrenciGrubu)
    {
        $ogrenciGrubu->load(['ustGrup', 'altGruplar', 'dersler']);

        return Inertia::render('OgrenciGruplari/Show', [
            'ogrenci_grubu' => $ogrenciGrubu,
        ]);
    }

    public function edit(OgrenciGrubu $ogrenciGrubu)
    {
        $ustGruplar = OgrenciGrubu::where('id', '!=', $ogrenciGrubu->id)
            ->orderBy('isim')
            ->get(['id', 'isim']);

        return Inertia::render('OgrenciGruplari/Edit', [
            'ogrenci_grubu' => $ogrenciGrubu,
            'ustGruplar' => $ustGruplar,
        ]);
    }

    public function update(Request $request, OgrenciGrubu $ogrenciGrubu)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'yil' => 'required|integer|min:1|max:6',
            'ogrenci_sayisi' => 'required|integer|min:1',
            'ust_grup_id' => 'nullable|exists:ogrenci_gruplari,id',
        ]);

        // Kendi kendinin üst grubu olmasını engelle
        if ($data['ust_grup_id'] == $ogrenciGrubu->id) {
            return back()->withErrors(['ust_grup_id' => 'Bir grup kendi üst grubu olamaz.']);
        }

        $ogrenciGrubu->update($data);

        return redirect()->route('ogrenci-gruplari.index')->with('success', 'Öğrenci grubu güncellendi.');
    }

    public function destroy(OgrenciGrubu $ogrenciGrubu)
    {
        $ogrenciGrubu->delete();

        return redirect()->route('ogrenci-gruplari.index')->with('success', 'Öğrenci grubu silindi.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new OgrenciGruplariTemplateExport, 'ogrenci-gruplari-sablonu.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $import = new OgrenciGruplariImport();
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
                    ->route('ogrenci-gruplari.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}, Hata: " . count($errorMessages))
                    ->with('import_errors', $errorMessages);
            }

            return redirect()
                ->route('ogrenci-gruplari.index')
                ->with('success', "Excel başarıyla yüklendi! Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}");

        } catch (\Exception $e) {
            return redirect()
                ->route('ogrenci-gruplari.index')
                ->with('error', 'Excel yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }
}
