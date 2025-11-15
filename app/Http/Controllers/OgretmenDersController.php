<?php

namespace App\Http\Controllers;

use App\Models\Ders;
use App\Models\Ogretmen;
use App\Models\OgretmenDers;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OgretmenDersController extends Controller
{
    public function index()
    {
        // Öğretmenleri listele ve ders sayılarını göster
        $ogretmenler = Ogretmen::orderBy('isim')
            ->get(['id', 'isim', 'unvan', 'email'])
            ->map(function ($ogretmen) {
                $ogretmen->ders_sayisi = OgretmenDers::where('ogretmen_id', $ogretmen->id)->count();
                return $ogretmen;
            });

        return Inertia::render('OgretmenDersleri/Index', [
            'ogretmenler' => $ogretmenler,
        ]);
    }

    public function create()
    {
        $ogretmenler = Ogretmen::orderBy('isim')->get(['id', 'isim', 'unvan']);
        $dersler = Ders::orderBy('isim')->get(['id', 'ders_kodu', 'isim']);

        return Inertia::render('OgretmenDersleri/Create', [
            'ogretmenler' => $ogretmenler,
            'dersler' => $dersler,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ogretmen_id' => 'required|exists:ogretmenler,id',
            'ders_id' => 'required|exists:dersler,id',
        ]);

        // Check if already exists
        $exists = OgretmenDers::where('ogretmen_id', $data['ogretmen_id'])
            ->where('ders_id', $data['ders_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['ders_id' => 'Bu öğretmen bu dersi zaten veriyor.']);
        }

        OgretmenDers::create($data);

        return redirect()->route('ogretmen-dersleri.index')
            ->with('success', 'Öğretmen dersi oluşturuldu.');
    }

    public function show($ogretmenId)
    {
        // Öğretmeni bul
        $ogretmen = Ogretmen::findOrFail($ogretmenId);

        // Tüm dersleri getir
        $tumDersler = Ders::orderBy('isim')->get();

        // Bu öğretmenin verdiği dersleri getir
        $ogretmenDersleri = OgretmenDers::where('ogretmen_id', $ogretmenId)
            ->with('ders')
            ->get()
            ->pluck('ders');

        return Inertia::render('OgretmenDersleri/Show', [
            'ogretmen' => $ogretmen,
            'dersler' => $ogretmenDersleri,
        ]);
    }

    public function edit($ogretmenId)
    {
        // Öğretmeni bul
        $ogretmen = Ogretmen::findOrFail($ogretmenId);

        // Tüm dersleri getir
        $tumDersler = Ders::orderBy('isim')->get();

        // Bu öğretmenin verdiği derslerin ID'lerini getir
        $seciliDersler = OgretmenDers::where('ogretmen_id', $ogretmenId)
            ->pluck('ders_id')
            ->toArray();

        return Inertia::render('OgretmenDersleri/Edit', [
            'ogretmen' => $ogretmen,
            'tum_dersler' => $tumDersler,
            'secili_dersler' => $seciliDersler,
        ]);
    }

    public function update(Request $request, $ogretmenId)
    {
        $data = $request->validate([
            'ders_ids' => 'required|array',
            'ders_ids.*' => 'exists:dersler,id',
        ]);

        // Öğretmenin mevcut tüm derslerini sil
        OgretmenDers::where('ogretmen_id', $ogretmenId)->delete();

        // Yeni dersleri ekle
        foreach ($data['ders_ids'] as $dersId) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmenId,
                'ders_id' => $dersId,
            ]);
        }

        return redirect()->route('ogretmen-dersleri.index')
            ->with('success', 'Öğretmen dersleri güncellendi.');
    }

    public function destroy($ogretmenId, $dersId)
    {
        OgretmenDers::where('ogretmen_id', $ogretmenId)
            ->where('ders_id', $dersId)
            ->delete();

        return redirect()->route('ogretmen-dersleri.index')
            ->with('success', 'Öğretmen dersi silindi.');
    }
}
