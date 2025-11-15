<?php

namespace App\Http\Controllers;

use App\Models\OgrenciGrubu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OgrenciGrubuController extends Controller
{
    public function index()
    {
        $grublar = OgrenciGrubu::with('ustGrup')
            ->orderBy('yil')
            ->orderBy('isim')
            ->get(['id', 'isim', 'yil', 'ogrenci_sayisi', 'ust_grup_id']);

        return Inertia::render('OgrenciGruplari/Index', [
            'ogrenci_gruplari' => $grublar,
        ]);
    }

    public function create()
    {
        $ustGruplar = OgrenciGrubu::orderBy('isim')->get(['id', 'isim']);

        return Inertia::render('OgrenciGruplari/Create', [
            'ustGruplar' => $ustGruplar,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'yil' => 'required|integer|min:1|max:6',
            'ogrenci_sayisi' => 'required|integer|min:1',
            'ust_grup_id' => 'nullable|exists:ogrenci_gruplari,id',
        ]);

        $grup = OgrenciGrubu::create($data);

        return redirect()->route('ogrenci-gruplari.index')->with('success', 'Öğrenci grubu oluşturuldu.');
    }

    public function show(OgrenciGrubu $ogrenciGrubu)
    {
        $ogrenciGrubu->load(['ustGrup', 'altGruplar', 'dersler']);

        return Inertia::render('OgrenciGruplari/Show', [
            'ogrenci_grubu' => $ogrenciGrubu,
        ]);
    }

    public function edit(OgrenciGrubu $ogrenciGrubu)
    {
        $ustGruplar = OgrenciGrubu::where('id', '!=', $ogrenciGrubu->id)
            ->orderBy('isim')
            ->get(['id', 'isim']);

        return Inertia::render('OgrenciGruplari/Edit', [
            'ogrenci_grubu' => $ogrenciGrubu,
            'ustGruplar' => $ustGruplar,
        ]);
    }

    public function update(Request $request, OgrenciGrubu $ogrenciGrubu)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'yil' => 'required|integer|min:1|max:6',
            'ogrenci_sayisi' => 'required|integer|min:1',
            'ust_grup_id' => 'nullable|exists:ogrenci_gruplari,id',
        ]);

        // Kendi kendinin üst grubu olmasını engelle
        if ($data['ust_grup_id'] == $ogrenciGrubu->id) {
            return back()->withErrors(['ust_grup_id' => 'Bir grup kendi üst grubu olamaz.']);
        }

        $ogrenciGrubu->update($data);

        return redirect()->route('ogrenci-gruplari.index')->with('success', 'Öğrenci grubu güncellendi.');
    }

    public function destroy(OgrenciGrubu $ogrenciGrubu)
    {
        $ogrenciGrubu->delete();

        return redirect()->route('ogrenci-gruplari.index')->with('success', 'Öğrenci grubu silindi.');
    }
}
