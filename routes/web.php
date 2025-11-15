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

    // Dersler resource routes
    Route::resource('dersler', DersController::class)->parameters(['dersler' => 'ders']);

    // Ogretmenler resource routes
    Route::resource('ogretmenler', OgretmenController::class)->parameters(['ogretmenler' => 'ogretmen']);

    // Mekanlar resource routes
    Route::resource('mekanlar', MekanController::class)->parameters(['mekanlar' => 'mekan']);

    // Ogrenci Gruplari resource routes
    Route::resource('ogrenci-gruplari', OgrenciGrubuController::class)->parameters(['ogrenci-gruplari' => 'ogrenciGrubu']);

    // Zaman Dilimleri resource routes
    Route::resource('zaman-dilimleri', ZamanDilimController::class)->parameters(['zaman-dilimleri' => 'zamanDilimi']);

    // Ders Mekan Gereksinimleri resource routes
    Route::resource('ders-mekan-gereksinimleri', DersMekanGeresinimController::class)->parameters(['ders-mekan-gereksinimleri' => 'dersMekanGereksinimi']);

    // Grup Dersleri resource routes (simplified for pivot)
    Route::get('grup-dersleri', [GrupDersController::class, 'index'])->name('grup-dersleri.index');
    Route::get('grup-dersleri/create', [GrupDersController::class, 'create'])->name('grup-dersleri.create');
    Route::post('grup-dersleri', [GrupDersController::class, 'store'])->name('grup-dersleri.store');
    Route::delete('grup-dersleri/{ogrenciGrupId}/{dersId}', [GrupDersController::class, 'destroy'])->name('grup-dersleri.destroy');

    // Ogretmen Dersleri resource routes (simplified for pivot)
    Route::get('ogretmen-dersleri', [OgretmenDersController::class, 'index'])->name('ogretmen-dersleri.index');
    Route::get('ogretmen-dersleri/create', [OgretmenDersController::class, 'create'])->name('ogretmen-dersleri.create');
    Route::post('ogretmen-dersleri', [OgretmenDersController::class, 'store'])->name('ogretmen-dersleri.store');
    Route::delete('ogretmen-dersleri/{ogretmenId}/{dersId}', [OgretmenDersController::class, 'destroy'])->name('ogretmen-dersleri.destroy');

    // Ogretmen Musaitlik resource routes
    Route::resource('ogretmen-musaitlik', OgretmenMusaitlikController::class)->parameters(['ogretmen-musaitlik' => 'ogretmenMusaitlik']);

    // Grup Kisitlamalari resource routes
    Route::resource('grup-kisitlamalari', GrupKisitlamaController::class)->parameters(['grup-kisitlamalari' => 'grupKisitlama']);
});


require __DIR__.'/settings.php';
