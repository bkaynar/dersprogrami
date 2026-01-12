<?php

namespace App\Http\Controllers;

use App\Exports\OgretmenMusaitlikTemplateExport;
use App\Imports\OgretmenMusaitlikImport;
use App\Models\Ogretmen;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class OgretmenMusaitlikController extends Controller
{
    public function index()
    {
        // Sadece öğretmenleri listele
        $ogretmenler = Ogretmen::orderBy('isim')
            ->get(['id', 'isim', 'unvan', 'email'])
            ->map(function ($ogretmen) {
                $ogretmen->musaitlikler_count = OgretmenMusaitlik::where('ogretmen_id', $ogretmen->id)->count();
                return $ogretmen;
            });

        return Inertia::render('OgretmenMusaitlik/Index', [
            'ogretmenler' => $ogretmenler,
        ]);
    }

    public function create()
    {
        $ogretmenler = Ogretmen::orderBy('isim')->get(['id', 'isim', 'unvan']);
        $zamanDilimleri = ZamanDilim::orderBy('haftanin_gunu')
            ->orderBy('baslangic_saati')
            ->get();

        return Inertia::render('OgretmenMusaitlik/Create', [
            'ogretmenler' => $ogretmenler,
            'zaman_dilimleri' => $zamanDilimleri,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ogretmen_id' => 'required|exists:ogretmenler,id',
            'zaman_dilimi_id' => 'required|exists:zaman_dilimleri,id',
            'musaitlik_tipi' => 'required|in:musait,musait_degil,tercih_edilen',
        ]);

        // Check if already exists
        $exists = OgretmenMusaitlik::where('ogretmen_id', $data['ogretmen_id'])
            ->where('zaman_dilimi_id', $data['zaman_dilimi_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['zaman_dilimi_id' => 'Bu öğretmen için bu zaman dilimi zaten tanımlı.']);
        }

        OgretmenMusaitlik::create($data);

        return redirect()->route('ogretmen-musaitlik.index')
            ->with('success', 'Öğretmen müsaitliği oluşturuldu.');
    }

    public function show($ogretmenId)
    {
        // Öğretmeni bul
        $ogretmen = Ogretmen::findOrFail($ogretmenId);

        // Gün sıralaması için map
        $gunSirasi = [
            'pazartesi' => 1,
            'sali' => 2,
            'carsamba' => 3,
            'persembe' => 4,
            'cuma' => 5,
            'cumartesi' => 6,
            'pazar' => 7,
        ];

        // Tüm zaman dilimlerini getir (string olarak bırak, sadece sırala)
        $zamanDilimleri = ZamanDilim::all()
            ->sortBy(function ($zd) use ($gunSirasi) {
                return [
                    $gunSirasi[$zd->haftanin_gunu] ?? 999,
                    $zd->baslangic_saati
                ];
            })
            ->values();

        // Bu öğretmenin müsaitlik kayıtlarını getir
        $musaitlikler = OgretmenMusaitlik::where('ogretmen_id', $ogretmenId)
            ->get()
            ->keyBy('zaman_dilimi_id');

        return Inertia::render('OgretmenMusaitlik/Show', [
            'ogretmen' => $ogretmen,
            'zaman_dilimleri' => $zamanDilimleri,
            'musaitlikler' => $musaitlikler,
        ]);
    }

    public function edit($ogretmenId)
    {
        // Öğretmeni bul
        $ogretmen = Ogretmen::findOrFail($ogretmenId);

        // Gün sıralaması için map
        $gunSirasi = [
            'pazartesi' => 1,
            'sali' => 2,
            'carsamba' => 3,
            'persembe' => 4,
            'cuma' => 5,
            'cumartesi' => 6,
            'pazar' => 7,
        ];

        // Tüm zaman dilimlerini getir (string olarak bırak, sadece sırala)
        $zamanDilimleri = ZamanDilim::all()
            ->sortBy(function ($zd) use ($gunSirasi) {
                return [
                    $gunSirasi[$zd->haftanin_gunu] ?? 999,
                    $zd->baslangic_saati
                ];
            })
            ->values();

        // Bu öğretmenin müsaitlik kayıtlarını getir
        $musaitlikler = OgretmenMusaitlik::where('ogretmen_id', $ogretmenId)
            ->get()
            ->keyBy('zaman_dilimi_id');

        return Inertia::render('OgretmenMusaitlik/Edit', [
            'ogretmen' => $ogretmen,
            'zaman_dilimleri' => $zamanDilimleri,
            'musaitlikler' => $musaitlikler,
        ]);
    }

    public function update(Request $request, $ogretmenId)
    {
        $data = $request->validate([
            'musaitlikler' => 'required|array',
        ]);

        // Öğretmenin mevcut tüm müsaitlik kayıtlarını sil
        OgretmenMusaitlik::where('ogretmen_id', $ogretmenId)->delete();

        // Yeni kayıtları oluştur (sadece seçili olanları)
        // Frontend'den gelen format: { zaman_dilimi_id: musaitlik_tipi }
        foreach ($data['musaitlikler'] as $zamanDilimiId => $musaitlikTipi) {
            if (!empty($musaitlikTipi)) {
                OgretmenMusaitlik::create([
                    'ogretmen_id' => $ogretmenId,
                    'zaman_dilimi_id' => $zamanDilimiId,
                    'musaitlik_tipi' => $musaitlikTipi,
                ]);
            }
        }

        return redirect()->route('ogretmen-musaitlik.show', $ogretmenId)
            ->with('success', 'Öğretmen müsaitliği güncellendi.');
    }

    public function destroy(OgretmenMusaitlik $ogretmenMusaitlik)
    {
        $ogretmenMusaitlik->delete();

        return redirect()->route('ogretmen-musaitlik.index')
            ->with('success', 'Öğretmen müsaitliği silindi.');
    }

    /**
     * Excel şablonunu indir
     */
    public function downloadTemplate()
    {
        return Excel::download(
            new OgretmenMusaitlikTemplateExport(),
            'ogretmen-musaitlik-sablonu-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Excel'den import et
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $import = new OgretmenMusaitlikImport();
            Excel::import($import, $request->file('file'));

            $stats = $import->getStats();
            $errors = $import->getErrors();

            if (count($errors) > 0) {
                return redirect()
                    ->route('ogretmen-musaitlik.index')
                    ->with('warning', "İşlem tamamlandı ancak bazı hatalar oluştu. Başarılı: {$stats['success']}, Hata: {$stats['errors']}")
                    ->with('import_errors', $errors);
            }

            return redirect()
                ->route('ogretmen-musaitlik.index')
                ->with('success', "Excel başarıyla içe aktarıldı! {$stats['success']} öğretmenin müsaitliği güncellendi.");
        } catch (\Exception $e) {
            return redirect()
                ->route('ogretmen-musaitlik.index')
                ->with('error', 'Excel içe aktarılırken hata oluştu: ' . $e->getMessage());
        }
    }
}
