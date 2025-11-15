<?php

namespace App\Http\Controllers;

use App\Models\GrupKisitlama;
use App\Models\OgrenciGrubu;
use App\Models\ZamanDilim;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GrupKisitlamaController extends Controller
{
    public function index()
    {
        $kisitlamalar = GrupKisitlama::with(['ogrenciGrubu', 'zamanDilimi'])
            ->get();

        return Inertia::render('GrupKisitlamalari/Index', [
            'kisitlamalar' => $kisitlamalar,
        ]);
    }

    public function create()
    {
        $gruplar = OgrenciGrubu::orderBy('isim')->get(['id', 'isim', 'yil']);
        $zamanDilimleri = ZamanDilim::orderBy('haftanin_gunu')
            ->orderBy('baslangic_saati')
            ->get();

        return Inertia::render('GrupKisitlamalari/Create', [
            'gruplar' => $gruplar,
            'zaman_dilimleri' => $zamanDilimleri,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ogrenci_grup_id' => 'required|exists:ogrenci_gruplari,id',
            'zaman_dilimi_id' => 'required|exists:zaman_dilimleri,id',
            'musait_mi' => 'required|boolean',
        ]);

        // Check if already exists
        $exists = GrupKisitlama::where('ogrenci_grup_id', $data['ogrenci_grup_id'])
            ->where('zaman_dilimi_id', $data['zaman_dilimi_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['zaman_dilimi_id' => 'Bu grup için bu zaman dilimi zaten tanımlı.']);
        }

        GrupKisitlama::create($data);

        return redirect()->route('grup-kisitlamalari.index')
            ->with('success', 'Grup kısıtlaması oluşturuldu.');
    }

    public function show(GrupKisitlama $grupKisitlama)
    {
        $grupKisitlama->load(['ogrenciGrubu', 'zamanDilimi']);

        return Inertia::render('GrupKisitlamalari/Show', [
            'kisitlama' => $grupKisitlama,
        ]);
    }

    public function edit(GrupKisitlama $grupKisitlama)
    {
        $gruplar = OgrenciGrubu::orderBy('isim')->get(['id', 'isim', 'yil']);
        $zamanDilimleri = ZamanDilim::orderBy('haftanin_gunu')
            ->orderBy('baslangic_saati')
            ->get();

        return Inertia::render('GrupKisitlamalari/Edit', [
            'kisitlama' => $grupKisitlama->load(['ogrenciGrubu', 'zamanDilimi']),
            'gruplar' => $gruplar,
            'zaman_dilimleri' => $zamanDilimleri,
        ]);
    }

    public function update(Request $request, GrupKisitlama $grupKisitlama)
    {
        $data = $request->validate([
            'ogrenci_grup_id' => 'required|exists:ogrenci_gruplari,id',
            'zaman_dilimi_id' => 'required|exists:zaman_dilimleri,id',
            'musait_mi' => 'required|boolean',
        ]);

        $grupKisitlama->update($data);

        return redirect()->route('grup-kisitlamalari.index')
            ->with('success', 'Grup kısıtlaması güncellendi.');
    }

    public function destroy(GrupKisitlama $grupKisitlama)
    {
        $grupKisitlama->delete();

        return redirect()->route('grup-kisitlamalari.index')
            ->with('success', 'Grup kısıtlaması silindi.');
    }
}
