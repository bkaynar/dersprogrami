<?php

namespace App\Http\Controllers;

use App\Models\ZamanDilim;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
