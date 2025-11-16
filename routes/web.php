<?php

use App\Http\Controllers\DersController;
use App\Http\Controllers\DersMekanGeresinimController;
use App\Http\Controllers\GrupDersController;
use App\Http\Controllers\GrupKisitlamaController;
use App\Http\Controllers\MekanController;
use App\Http\Controllers\OgrenciGrubuController;
use App\Http\Controllers\OgretmenController;
use App\Http\Controllers\OgretmenDersController;
use App\Http\Controllers\OgretmenMusaitlikController;
use App\Http\Controllers\ProgramOlusturController;
use App\Http\Controllers\ZamanDilimController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Models\Ders;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Dersler - Excel import/export routes (önce tanımla)
    Route::get('dersler/template/download', [DersController::class, 'downloadTemplate'])->name('dersler.template.download');
    Route::post('dersler/import', [DersController::class, 'import'])->name('dersler.import');

    // Dersler resource routes
    Route::resource('dersler', DersController::class)->parameters(['dersler' => 'ders']);

    // Ogretmenler - Excel import/export routes (önce tanımla)
    Route::get('ogretmenler/template/download', [OgretmenController::class, 'downloadTemplate'])->name('ogretmenler.template.download');
    Route::post('ogretmenler/import', [OgretmenController::class, 'import'])->name('ogretmenler.import');

    // Ogretmenler resource routes
    Route::resource('ogretmenler', OgretmenController::class)->parameters(['ogretmenler' => 'ogretmen']);

    // Mekanlar - Excel import/export routes (önce tanımla)
    Route::get('mekanlar/template/download', [MekanController::class, 'downloadTemplate'])->name('mekanlar.template.download');
    Route::post('mekanlar/import', [MekanController::class, 'import'])->name('mekanlar.import');

    // Ogrenci Gruplari - Excel import/export routes (önce tanımla)
    Route::get('ogrenci-gruplari/template/download', [OgrenciGrubuController::class, 'downloadTemplate'])->name('ogrenci-gruplari.template.download');
    Route::post('ogrenci-gruplari/import', [OgrenciGrubuController::class, 'import'])->name('ogrenci-gruplari.import');

    // Mekanlar resource routes
    Route::resource('mekanlar', MekanController::class)->parameters(['mekanlar' => 'mekan']);

    // Ogrenci Gruplari resource routes
    Route::resource('ogrenci-gruplari', OgrenciGrubuController::class)->parameters(['ogrenci-gruplari' => 'ogrenciGrubu']);

    // Zaman Dilimleri resource routes
    Route::resource('zaman-dilimleri', ZamanDilimController::class)->parameters(['zaman-dilimleri' => 'zamanDilimi']);

    // Ders Mekan Gereksinimleri resource routes
    Route::resource('ders-mekan-gereksinimleri', DersMekanGeresinimController::class)->parameters(['ders-mekan-gereksinimleri' => 'dersMekanGereksinimi']);

    // Grup Dersleri routes (grup bazlı)
    Route::get('grup-dersleri', [GrupDersController::class, 'index'])->name('grup-dersleri.index');
    Route::get('grup-dersleri/create', [GrupDersController::class, 'create'])->name('grup-dersleri.create');
    Route::post('grup-dersleri', [GrupDersController::class, 'store'])->name('grup-dersleri.store');
    Route::get('grup-dersleri/{grup}', [GrupDersController::class, 'show'])->name('grup-dersleri.show');
    Route::get('grup-dersleri/{grup}/edit', [GrupDersController::class, 'edit'])->name('grup-dersleri.edit');
    Route::put('grup-dersleri/{grup}', [GrupDersController::class, 'update'])->name('grup-dersleri.update');
    Route::delete('grup-dersleri/{ogrenciGrupId}/{dersId}', [GrupDersController::class, 'destroy'])->name('grup-dersleri.destroy');

    // Ogretmen Dersleri routes (öğretmen bazlı)
    Route::get('ogretmen-dersleri', [OgretmenDersController::class, 'index'])->name('ogretmen-dersleri.index');
    Route::get('ogretmen-dersleri/create', [OgretmenDersController::class, 'create'])->name('ogretmen-dersleri.create');
    Route::post('ogretmen-dersleri', [OgretmenDersController::class, 'store'])->name('ogretmen-dersleri.store');
    Route::get('ogretmen-dersleri/{ogretmen}', [OgretmenDersController::class, 'show'])->name('ogretmen-dersleri.show');
    Route::get('ogretmen-dersleri/{ogretmen}/edit', [OgretmenDersController::class, 'edit'])->name('ogretmen-dersleri.edit');
    Route::put('ogretmen-dersleri/{ogretmen}', [OgretmenDersController::class, 'update'])->name('ogretmen-dersleri.update');
    Route::delete('ogretmen-dersleri/{ogretmenId}/{dersId}', [OgretmenDersController::class, 'destroy'])->name('ogretmen-dersleri.destroy');

    // Ogretmen Musaitlik routes (öğretmen bazlı)
    Route::get('ogretmen-musaitlik', [OgretmenMusaitlikController::class, 'index'])->name('ogretmen-musaitlik.index');
    Route::get('ogretmen-musaitlik/create', [OgretmenMusaitlikController::class, 'create'])->name('ogretmen-musaitlik.create');
    Route::post('ogretmen-musaitlik', [OgretmenMusaitlikController::class, 'store'])->name('ogretmen-musaitlik.store');
    Route::get('ogretmen-musaitlik/{ogretmen}', [OgretmenMusaitlikController::class, 'show'])->name('ogretmen-musaitlik.show');
    Route::get('ogretmen-musaitlik/{ogretmen}/edit', [OgretmenMusaitlikController::class, 'edit'])->name('ogretmen-musaitlik.edit');
    Route::put('ogretmen-musaitlik/{ogretmen}', [OgretmenMusaitlikController::class, 'update'])->name('ogretmen-musaitlik.update');
    Route::delete('ogretmen-musaitlik/{ogretmen}', [OgretmenMusaitlikController::class, 'destroy'])->name('ogretmen-musaitlik.destroy');

    // Grup Kisitlamalari resource routes
    Route::resource('grup-kisitlamalari', GrupKisitlamaController::class)->parameters(['grup-kisitlamalari' => 'grupKisitlama']);

    // Program Olustur routes
    Route::get('program-olustur', [ProgramOlusturController::class, 'index'])->name('program-olustur.index');
    Route::post('program-olustur/generate', [ProgramOlusturController::class, 'generate'])->name('program-olustur.generate');
    Route::get('program-olustur/status', [ProgramOlusturController::class, 'status'])->name('program-olustur.status');
    Route::get('program-olustur/show', [ProgramOlusturController::class, 'show'])->name('program-olustur.show');
    Route::get('program-olustur/export/excel', [ProgramOlusturController::class, 'exportExcel'])->name('program-olustur.export.excel');
    Route::get('program-olustur/export/pdf', [ProgramOlusturController::class, 'exportPdf'])->name('program-olustur.export.pdf');
    Route::delete('program-olustur', [ProgramOlusturController::class, 'destroy'])->name('program-olustur.destroy');
});


require __DIR__.'/settings.php';
