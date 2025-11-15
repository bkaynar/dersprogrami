<?php

namespace App\Http\Controllers;

use App\Models\Ders;
use App\Models\GrupDers;
use App\Models\OgrenciGrubu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GrupDersController extends Controller
{
    public function index()
    {
        // Grupları listele ve ders sayılarını göster
        $gruplar = OgrenciGrubu::orderBy('isim')
            ->get(['id', 'isim', 'yil'])
            ->map(function ($grup) {
                $grup->ders_sayisi = GrupDers::where('ogrenci_grup_id', $grup->id)->count();
                return $grup;
            });

        return Inertia::render('GrupDersleri/Index', [
            'gruplar' => $gruplar,
        ]);
    }

    public function create()
    {
        $gruplar = OgrenciGrubu::orderBy('isim')->get(['id', 'isim', 'yil']);
        $dersler = Ders::orderBy('isim')->get(['id', 'ders_kodu', 'isim']);

        return Inertia::render('GrupDersleri/Create', [
            'gruplar' => $gruplar,
            'dersler' => $dersler,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ogrenci_grup_id' => 'required|exists:ogrenci_gruplari,id',
            'ders_id' => 'required|exists:dersler,id',
        ]);

        // Check if already exists
        $exists = GrupDers::where('ogrenci_grup_id', $data['ogrenci_grup_id'])
            ->where('ders_id', $data['ders_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['ders_id' => 'Bu grup bu dersi zaten alıyor.']);
        }

        GrupDers::create($data);

        return redirect()->route('grup-dersleri.index')
            ->with('success', 'Grup dersi oluşturuldu.');
    }

    public function show($grupId)
    {
        // Grubu bul
        $grup = OgrenciGrubu::findOrFail($grupId);

        // Bu grubun aldığı dersleri getir
        $grupDersleri = GrupDers::where('ogrenci_grup_id', $grupId)
            ->with('ders')
            ->get()
            ->pluck('ders');

        return Inertia::render('GrupDersleri/Show', [
            'grup' => $grup,
            'dersler' => $grupDersleri,
        ]);
    }

    public function edit($grupId)
    {
        // Grubu bul
        $grup = OgrenciGrubu::findOrFail($grupId);

        // Tüm dersleri getir
        $tumDersler = Ders::orderBy('isim')->get();

        // Bu grubun aldığı derslerin ID'lerini getir
        $seciliDersler = GrupDers::where('ogrenci_grup_id', $grupId)
            ->pluck('ders_id')
            ->toArray();

        return Inertia::render('GrupDersleri/Edit', [
            'grup' => $grup,
            'tum_dersler' => $tumDersler,
            'secili_dersler' => $seciliDersler,
        ]);
    }

    public function update(Request $request, $grupId)
    {
        $data = $request->validate([
            'ders_ids' => 'required|array',
            'ders_ids.*' => 'exists:dersler,id',
        ]);

        // Grubun mevcut tüm derslerini sil
        GrupDers::where('ogrenci_grup_id', $grupId)->delete();

        // Yeni dersleri ekle
        foreach ($data['ders_ids'] as $dersId) {
            GrupDers::create([
                'ogrenci_grup_id' => $grupId,
                'ders_id' => $dersId,
            ]);
        }

        return redirect()->route('grup-dersleri.index')
            ->with('success', 'Grup dersleri güncellendi.');
    }

    public function destroy($ogrenciGrupId, $dersId)
    {
        GrupDers::where('ogrenci_grup_id', $ogrenciGrupId)
            ->where('ders_id', $dersId)
            ->delete();

        return redirect()->route('grup-dersleri.index')
            ->with('success', 'Grup dersi silindi.');
    }
}
