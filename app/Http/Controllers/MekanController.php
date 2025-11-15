<?php

namespace App\Http\Controllers;

use App\Models\Mekan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MekanController extends Controller
{
    public function index()
    {
        $mekanlar = Mekan::orderBy('isim')->get(['id', 'isim', 'kapasite', 'mekan_tipi']);

        return Inertia::render('Mekanlar/Index', [
            'mekanlar' => $mekanlar,
        ]);
    }

    public function create()
    {
        return Inertia::render('Mekanlar/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'kapasite' => 'required|integer|min:1',
            'mekan_tipi' => 'nullable|string|max:100',
        ]);

        $mekan = Mekan::create($data);

        return redirect()->route('mekanlar.index')->with('success', 'Mekan oluşturuldu.');
    }

    public function show(Mekan $mekan)
    {
        return Inertia::render('Mekanlar/Show', [
            'mekan' => $mekan,
        ]);
    }

    public function edit(Mekan $mekan)
    {
        return Inertia::render('Mekanlar/Edit', [
            'mekan' => $mekan,
        ]);
    }

    public function update(Request $request, Mekan $mekan)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'kapasite' => 'required|integer|min:1',
            'mekan_tipi' => 'nullable|string|max:100',
        ]);

        $mekan->update($data);

        return redirect()->route('mekanlar.index')->with('success', 'Mekan güncellendi.');
    }

    public function destroy(Mekan $mekan)
    {
        $mekan->delete();

        return redirect()->route('mekanlar.index')->with('success', 'Mekan silindi.');
    }
}
