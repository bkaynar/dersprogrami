<?php

namespace App\Http\Controllers;

use App\Models\ZamanDilim;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Imports\ZamanDilimleriImport;
use App\Exports\ZamanDilimleriTemplateExport;
use Maatwebsite\Excel\Facades\Excel;

class ZamanDilimController extends Controller
{
    public function index()
    {
        $zamanDilimleri = ZamanDilim::orderBy('haftanin_gunu')
            ->orderBy('baslangic_saati')
            ->get(['id', 'haftanin_gunu', 'baslangic_saati', 'bitis_saati']);

        return Inertia::render('ZamanDilimleri/Index', [
            'zaman_dilimleri' => $zamanDilimleri,
        ]);
    }

    public function create()
    {
        return Inertia::render('ZamanDilimleri/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'haftanin_gunu' => 'required|in:pazartesi,sali,carsamba,persembe,cuma,cumartesi,pazar',
            'baslangic_saati' => 'required|date_format:H:i',
            'bitis_saati' => 'required|date_format:H:i|after:baslangic_saati',
        ]);

        $zamanDilimi = ZamanDilim::create($data);

        return redirect()->route('zaman-dilimleri.index')->with('success', 'Zaman dilimi oluşturuldu.');
    }

    public function show(ZamanDilim $zamanDilimi)
    {
        return Inertia::render('ZamanDilimleri/Show', [
            'zaman_dilimi' => $zamanDilimi,
        ]);
    }

    public function edit(ZamanDilim $zamanDilimi)
    {
        return Inertia::render('ZamanDilimleri/Edit', [
            'zaman_dilimi' => $zamanDilimi,
        ]);
    }

    public function update(Request $request, ZamanDilim $zamanDilimi)
    {
        $data = $request->validate([
            'haftanin_gunu' => 'required|in:pazartesi,sali,carsamba,persembe,cuma,cumartesi,pazar',
            'baslangic_saati' => 'required|date_format:H:i',
            'bitis_saati' => 'required|date_format:H:i|after:baslangic_saati',
        ]);

        $zamanDilimi->update($data);

        return redirect()->route('zaman-dilimleri.index')->with('success', 'Zaman dilimi güncellendi.');
    }

    public function destroy(ZamanDilim $zamanDilimi)
    {
        $zamanDilimi->delete();

        return redirect()->route('zaman-dilimleri.index')->with('success', 'Zaman dilimi silindi.');
    }

    public function downloadTemplate()
    {
        return Excel::download(new ZamanDilimleriTemplateExport, 'zaman-dilimleri-sablonu.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $import = new ZamanDilimleriImport();
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
                    ->route('zaman-dilimleri.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}, Hata: " . count($errorMessages))
                    ->with('import_errors', $errorMessages);
            }

            return redirect()
                ->route('zaman-dilimleri.index')
                ->with('success', "Excel başarıyla yüklendi! Eklenen: {$stats['success']}, Güncellenen: {$stats['updated']}");

        } catch (\Exception $e) {
            return redirect()
                ->route('zaman-dilimleri.index')
                ->with('error', 'Excel yüklenirken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Otomatik zaman dilimi oluşturma
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'baslangic_saati' => 'required|date_format:H:i',
            'bitis_saati' => 'required|date_format:H:i|after:baslangic_saati',
            'ders_suresi' => 'required|integer|min:30|max:120',
            'teneffus_suresi' => 'required|integer|min:0|max:30',
            'ogle_arasi_baslangic' => 'required|date_format:H:i',
            'ogle_arasi_bitis' => 'required|date_format:H:i|after:ogle_arasi_baslangic',
            'pazartesi' => 'boolean',
            'sali' => 'boolean',
            'carsamba' => 'boolean',
            'persembe' => 'boolean',
            'cuma' => 'boolean',
            'cumartesi' => 'boolean',
            'pazar' => 'boolean',
        ]);

        // Seçilen günleri topla
        $gunler = [];
        $gunIsimleri = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma', 'cumartesi', 'pazar'];
        foreach ($gunIsimleri as $gun) {
            if (!empty($data[$gun])) {
                $gunler[] = $gun;
            }
        }

        if (empty($gunler)) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'En az bir gün seçmelisiniz.']);
        }

        // Zaman aralıklarını hesapla
        $baslangic = strtotime($data['baslangic_saati']);
        $bitis = strtotime($data['bitis_saati']);
        $ogleBaslangic = strtotime($data['ogle_arasi_baslangic']);
        $ogleBitis = strtotime($data['ogle_arasi_bitis']);
        $dersSuresi = $data['ders_suresi'] * 60; // dakikayı saniyeye çevir
        $teneffusSuresi = $data['teneffus_suresi'] * 60;

        $createdCount = 0;
        $updatedCount = 0;

        try {
            foreach ($gunler as $gun) {
                $current = $baslangic;

                while ($current + $dersSuresi <= $bitis) {
                    $slotBaslangic = date('H:i', $current);
                    $slotBitis = date('H:i', $current + $dersSuresi);

                    // Öğle arası kontrolü - bu zaman dilimi öğle arasına denk geliyorsa atla
                    if ($current >= $ogleBaslangic && $current < $ogleBitis) {
                        $current = $ogleBitis;
                        continue;
                    }

                    // Zaman dilimini oluştur veya güncelle
                    $zamanDilimi = ZamanDilim::updateOrCreate(
                        [
                            'haftanin_gunu' => $gun,
                            'baslangic_saati' => $slotBaslangic,
                        ],
                        [
                            'bitis_saati' => $slotBitis,
                        ]
                    );

                    if ($zamanDilimi->wasRecentlyCreated) {
                        $createdCount++;
                    } else {
                        $updatedCount++;
                    }

                    $current += $dersSuresi + $teneffusSuresi;
                }
            }

            return redirect()
                ->route('zaman-dilimleri.index')
                ->with('success', "Zaman dilimleri başarıyla oluşturuldu! Eklenen: {$createdCount}, Güncellenen: {$updatedCount}");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Zaman dilimleri oluşturulurken hata oluştu: ' . $e->getMessage())
                ->withInput();
        }
    }
}
