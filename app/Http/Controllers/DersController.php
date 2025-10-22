<?php

namespace App\Http\Controllers;

use App\Models\Ders;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DersController extends Controller
{
    public function index()
    {
        $dersler = Ders::orderBy('isim')->get(['id', 'ders_kodu', 'isim', 'haftalik_saat']);

        return Inertia::render('Dersler/Index', [
            'dersler' => $dersler,
        ]);
    }

    public function create()
    {
        return Inertia::render('Dersler/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ders_kodu' => 'required|string|max:50|unique:dersler,ders_kodu',
            'isim' => 'required|string|max:255',
            'haftalik_saat' => 'required|integer|min:0',
        ]);

        $ders = Ders::create($data);

        return redirect()->route('dersler.index')->with('success', 'Ders oluşturuldu.');
    }

    public function show(Ders $ders)
    {
        return Inertia::render('Dersler/Show', [
            'ders' => $ders,
        ]);
    }

    public function edit(Ders $ders)
    {
        return Inertia::render('Dersler/Edit', [
            'ders' => $ders,
        ]);
    }

    public function update(Request $request, Ders $ders)
    {
        $data = $request->validate([
            'ders_kodu' => 'required|string|max:50|unique:dersler,ders_kodu,' . $ders->id,
            'isim' => 'required|string|max:255',
            'haftalik_saat' => 'required|integer|min:0',
        ]);

        $ders->update($data);

        return redirect()->route('dersler.index')->with('success', 'Ders güncellendi.');
    }

    public function destroy(Ders $ders)
    {
        $ders->delete();

        return redirect()->route('dersler.index')->with('success', 'Ders silindi.');
    }
}
