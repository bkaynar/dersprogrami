<?php

namespace App\Http\Controllers;

use App\Models\Ogretmen;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        // Gün isimlerini numaraya çevir
        $gunMap = [
            'pazartesi' => 1,
            'sali' => 2,
            'carsamba' => 3,
            'persembe' => 4,
            'cuma' => 5,
            'cumartesi' => 6,
            'pazar' => 7,
        ];

        // Tüm zaman dilimlerini getir ve gün numarasını ekle
        $zamanDilimleri = ZamanDilim::all()->map(function ($zd) use ($gunMap) {
            $zd->haftanin_gunu = $gunMap[$zd->haftanin_gunu] ?? 1;
            return $zd;
        })->sortBy([
            ['haftanin_gunu', 'asc'],
            ['baslangic_saati', 'asc'],
        ])->values();

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

        // Gün isimlerini numaraya çevir
        $gunMap = [
            'pazartesi' => 1,
            'sali' => 2,
            'carsamba' => 3,
            'persembe' => 4,
            'cuma' => 5,
            'cumartesi' => 6,
            'pazar' => 7,
        ];

        // Tüm zaman dilimlerini getir ve gün numarasını ekle
        $zamanDilimleri = ZamanDilim::all()->map(function ($zd) use ($gunMap) {
            $zd->haftanin_gunu = $gunMap[$zd->haftanin_gunu] ?? 1;
            return $zd;
        })->sortBy([
            ['haftanin_gunu', 'asc'],
            ['baslangic_saati', 'asc'],
        ])->values();

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
            'musaitlikler.*.zaman_dilimi_id' => 'required|exists:zaman_dilimleri,id',
            'musaitlikler.*.musaitlik_tipi' => 'nullable|in:musait,musait_degil,tercih_edilen',
        ]);

        // Öğretmenin mevcut tüm müsaitlik kayıtlarını sil
        OgretmenMusaitlik::where('ogretmen_id', $ogretmenId)->delete();

        // Yeni kayıtları oluştur (sadece seçili olanları)
        foreach ($data['musaitlikler'] as $musaitlik) {
            if (!empty($musaitlik['musaitlik_tipi'])) {
                OgretmenMusaitlik::create([
                    'ogretmen_id' => $ogretmenId,
                    'zaman_dilimi_id' => $musaitlik['zaman_dilimi_id'],
                    'musaitlik_tipi' => $musaitlik['musaitlik_tipi'],
                ]);
            }
        }

        return redirect()->route('ogretmen-musaitlik.index')
            ->with('success', 'Öğretmen müsaitliği güncellendi.');
    }

    public function destroy(OgretmenMusaitlik $ogretmenMusaitlik)
    {
        $ogretmenMusaitlik->delete();

        return redirect()->route('ogretmen-musaitlik.index')
            ->with('success', 'Öğretmen müsaitliği silindi.');
    }
}
