<?php

namespace App\Http\Controllers;

use App\Models\Ders;
use App\Models\DersMekanGereksinimi;
use App\Imports\DersMekanGereksinimleriImport;
use App\Exports\DersMekanGereksinimleriTemplateExport;
use App\Exports\DersMekanGereksinimleriExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DersMekanGeresinimController extends Controller
{
    public function index()
    {
        // Tüm dersleri, mekan gereksinimleri ile birlikte getir
        $dersler = Ders::with('mekanGereksinimi')
            ->orderBy('isim')
            ->get();

        return Inertia::render('DersMekanGereksinimleri/Index', [
            'dersler' => $dersler,
        ]);
    }

    public function create()
    {
        // Bu metod artık doğrudan edit sayfasına yönlendirebilir veya kullanılmayabilir
        return redirect()->route('ders-mekan-gereksinimleri.index');
    }

    public function store(Request $request)
    {
        // Store işlemi update içinde handle edilecek
        return redirect()->route('ders-mekan-gereksinimleri.index');
    }

    public function show(Ders $ders)
    {
        $ders->load('mekanGereksinimi');

        return Inertia::render('DersMekanGereksinimleri/Show', [
            'ders' => $ders,
        ]);
    }

    public function edit(Ders $ders)
    {
        $ders->load('mekanGereksinimi');

        return Inertia::render('DersMekanGereksinimleri/Edit', [
            'ders' => $ders,
        ]);
    }

    public function update(Request $request, Ders $ders)
    {
        $data = $request->validate([
            'mekan_tipi' => 'nullable|in:derslik,laboratuvar,konferans_salonu',
            'gereksinim_tipi' => 'nullable|in:zorunlu,olabilir',
            'has_requirement' => 'boolean', // Gereksinim var mı yok mu kontrolü
        ]);

        // Eğer gereksinim yok olarak işaretlendiyse sil
        if (isset($data['has_requirement']) && !$data['has_requirement']) {
            if ($ders->mekanGereksinimi) {
                $ders->mekanGereksinimi->delete();
            }
            return redirect()->route('ders-mekan-gereksinimleri.index')
                ->with('success', 'Ders mekan gereksinimi kaldırıldı.');
        }

        // Validasyon (Eğer gereksinim varsa tipler zorunlu)
        if ($data['has_requirement']) {
            $request->validate([
                'mekan_tipi' => 'required|in:derslik,laboratuvar,konferans_salonu',
                'gereksinim_tipi' => 'required|in:zorunlu,olabilir',
            ]);
        }

        // Kaydı güncelle veya oluştur
        DersMekanGereksinimi::updateOrCreate(
            ['ders_id' => $ders->id],
            [
                'mekan_tipi' => $data['mekan_tipi'],
                'gereksinim_tipi' => $data['gereksinim_tipi'],
            ]
        );

        return redirect()->route('ders-mekan-gereksinimleri.index')
            ->with('success', 'Ders mekan gereksinimi güncellendi.');
    }

    public function destroy(Ders $ders)
    {
        if ($ders->mekanGereksinimi) {
            $ders->mekanGereksinimi->delete();
        }

        return redirect()->route('ders-mekan-gereksinimleri.index')
            ->with('success', 'Ders mekan gereksinimi silindi.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new DersMekanGereksinimleriTemplateExport, 'ders-mekan-gereksinimleri-sablonu.xlsx');
    }

    public function export()
    {
        return Excel::download(new DersMekanGereksinimleriExport, 'ders-mekan-gereksinimleri.xlsx');
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