<?php

namespace App\Http\Controllers;

use App\Models\Ogretmen;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OgretmenController extends Controller
{
    public function index()
    {
        $ogretmenler = Ogretmen::orderBy('isim')->get(['id', 'isim', 'unvan', 'email']);

        return Inertia::render('Ogretmenler/Index', [
            'ogretmenler' => $ogretmenler,
        ]);
    }

    public function create()
    {
        return Inertia::render('Ogretmenler/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'unvan' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:ogretmenler,email',
        ]);

        $ogretmen = Ogretmen::create($data);

        return redirect()->route('ogretmenler.index')->with('success', 'Öğretmen oluşturuldu.');
    }

    public function show(Ogretmen $ogretmen)
    {
        return Inertia::render('Ogretmenler/Show', [
            'ogretmen' => $ogretmen,
        ]);
    }

    public function edit(Ogretmen $ogretmen)
    {
        return Inertia::render('Ogretmenler/Edit', [
            'ogretmen' => $ogretmen,
        ]);
    }

    public function update(Request $request, Ogretmen $ogretmen)
    {
        $data = $request->validate([
            'isim' => 'required|string|max:255',
            'unvan' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:ogretmenler,email,' . $ogretmen->id,
        ]);

        $ogretmen->update($data);

        return redirect()->route('ogretmenler.index')->with('success', 'Öğretmen güncellendi.');
    }

    public function destroy(Ogretmen $ogretmen)
    {
        $ogretmen->delete();

        return redirect()->route('ogretmenler.index')->with('success', 'Öğretmen silindi.');
    }
}
