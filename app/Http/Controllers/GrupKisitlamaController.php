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
        // Öğrenci gruplarını listele
        $gruplar = OgrenciGrubu::orderBy('isim')
            ->get(['id', 'isim', 'yil'])
            ->map(function ($grup) {
                $grup->kisitlamalar_count = GrupKisitlama::where('ogrenci_grup_id', $grup->id)->count();
                return $grup;
            });

        return Inertia::render('GrupKisitlamalari/Index', [
            'gruplar' => $gruplar,
        ]);
    }

    public function create()
    {
        // Bu metod artık kullanılmayacak veya toplu seçim sayfasına yönlendirebilir
        return redirect()->route('grup-kisitlamalari.index');
    }

    public function store(Request $request)
    {
        // Bu metod artık kullanılmayacak
        return redirect()->route('grup-kisitlamalari.index');
    }

    public function show($grupId)
    {
        $grup = OgrenciGrubu::findOrFail($grupId);

        // Gün sıralaması
        $gunSirasi = [
            'pazartesi' => 1,
            'sali' => 2,
            'carsamba' => 3,
            'persembe' => 4,
            'cuma' => 5,
            'cumartesi' => 6,
            'pazar' => 7,
        ];

        $zamanDilimleri = ZamanDilim::all()
            ->sortBy(function ($zd) use ($gunSirasi) {
                return [
                    $gunSirasi[$zd->haftanin_gunu] ?? 999,
                    $zd->baslangic_saati
                ];
            })
            ->values();

        $kisitlamalar = GrupKisitlama::where('ogrenci_grup_id', $grupId)
            ->get()
            ->keyBy('zaman_dilimi_id');

        return Inertia::render('GrupKisitlamalari/Show', [
            'grup' => $grup,
            'zaman_dilimleri' => $zamanDilimleri,
            'kisitlamalar' => $kisitlamalar,
        ]);
    }

    public function edit($grupId)
    {
        $grup = OgrenciGrubu::findOrFail($grupId);

        $gunSirasi = [
            'pazartesi' => 1,
            'sali' => 2,
            'carsamba' => 3,
            'persembe' => 4,
            'cuma' => 5,
            'cumartesi' => 6,
            'pazar' => 7,
        ];

        $zamanDilimleri = ZamanDilim::all()
            ->sortBy(function ($zd) use ($gunSirasi) {
                return [
                    $gunSirasi[$zd->haftanin_gunu] ?? 999,
                    $zd->baslangic_saati
                ];
            })
            ->values();

        $kisitlamalar = GrupKisitlama::where('ogrenci_grup_id', $grupId)
            ->get()
            ->keyBy('zaman_dilimi_id');

        return Inertia::render('GrupKisitlamalari/Edit', [
            'grup' => $grup,
            'zaman_dilimleri' => $zamanDilimleri,
            'kisitlamalar' => $kisitlamalar,
        ]);
    }

    public function update(Request $request, $grupId)
    {
        $data = $request->validate([
            'kisitlamalar' => 'required|array',
        ]);

        // Grubun mevcut tüm kısıtlamalarını sil
        GrupKisitlama::where('ogrenci_grup_id', $grupId)->delete();

        // Yeni kayıtları oluştur
        // Frontend: { zaman_dilimi_id: musait_mi (boolean/string) }
        foreach ($data['kisitlamalar'] as $zamanDilimiId => $musaitMi) {
            // musait_mi null ise atla (belirtilmemiş)
            // Frontend true/false/'true'/'false' gönderebilir
            if ($musaitMi !== null) {
                // String 'false' gelirse boolean false yap
                $isAvailable = filter_var($musaitMi, FILTER_VALIDATE_BOOLEAN);
                
                GrupKisitlama::create([
                    'ogrenci_grup_id' => $grupId,
                    'zaman_dilimi_id' => $zamanDilimiId,
                    'musait_mi' => $isAvailable,
                ]);
            }
        }

        return redirect()->route('grup-kisitlamalari.show', $grupId)
            ->with('success', 'Grup kısıtlamaları güncellendi.');
    }

    public function destroy($grupId)
    {
        // Grubun tüm kısıtlamalarını sil
        GrupKisitlama::where('ogrenci_grup_id', $grupId)->delete();

        return redirect()->route('grup-kisitlamalari.index')
            ->with('success', 'Grup kısıtlamaları temizlendi.');
    }
}