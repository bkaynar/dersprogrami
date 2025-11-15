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
        $grupDersleri = GrupDers::with(['ogrenciGrubu', 'ders'])
            ->get();

        return Inertia::render('GrupDersleri/Index', [
            'grup_dersleri' => $grupDersleri,
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

    public function destroy($ogrenciGrupId, $dersId)
    {
        GrupDers::where('ogrenci_grup_id', $ogrenciGrupId)
            ->where('ders_id', $dersId)
            ->delete();

        return redirect()->route('grup-dersleri.index')
            ->with('success', 'Grup dersi silindi.');
    }
}
