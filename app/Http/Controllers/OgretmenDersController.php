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
        $ogretmenDersleri = OgretmenDers::with(['ogretmen', 'ders'])
            ->get();

        return Inertia::render('OgretmenDersleri/Index', [
            'ogretmen_dersleri' => $ogretmenDersleri,
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

    public function destroy($ogretmenId, $dersId)
    {
        OgretmenDers::where('ogretmen_id', $ogretmenId)
            ->where('ders_id', $dersId)
            ->delete();

        return redirect()->route('ogretmen-dersleri.index')
            ->with('success', 'Öğretmen dersi silindi.');
    }
}
