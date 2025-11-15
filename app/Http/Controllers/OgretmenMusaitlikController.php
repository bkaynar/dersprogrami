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
        $musaitlikler = OgretmenMusaitlik::with(['ogretmen', 'zamanDilimi'])
            ->get();

        return Inertia::render('OgretmenMusaitlik/Index', [
            'musaitlikler' => $musaitlikler,
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

    public function show(OgretmenMusaitlik $ogretmenMusaitlik)
    {
        $ogretmenMusaitlik->load(['ogretmen', 'zamanDilimi']);

        return Inertia::render('OgretmenMusaitlik/Show', [
            'musaitlik' => $ogretmenMusaitlik,
        ]);
    }

    public function edit(OgretmenMusaitlik $ogretmenMusaitlik)
    {
        $ogretmenler = Ogretmen::orderBy('isim')->get(['id', 'isim', 'unvan']);
        $zamanDilimleri = ZamanDilim::orderBy('haftanin_gunu')
            ->orderBy('baslangic_saati')
            ->get();

        return Inertia::render('OgretmenMusaitlik/Edit', [
            'musaitlik' => $ogretmenMusaitlik->load(['ogretmen', 'zamanDilimi']),
            'ogretmenler' => $ogretmenler,
            'zaman_dilimleri' => $zamanDilimleri,
        ]);
    }

    public function update(Request $request, OgretmenMusaitlik $ogretmenMusaitlik)
    {
        $data = $request->validate([
            'ogretmen_id' => 'required|exists:ogretmenler,id',
            'zaman_dilimi_id' => 'required|exists:zaman_dilimleri,id',
            'musaitlik_tipi' => 'required|in:musait,musait_degil,tercih_edilen',
        ]);

        $ogretmenMusaitlik->update($data);

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
