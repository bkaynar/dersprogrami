<?php

use App\Http\Controllers\DersController;
use App\Http\Controllers\OgretmenController;
use App\Http\Controllers\MekanController;
use App\Http\Controllers\OgrenciGrubuController;
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
});


require __DIR__.'/settings.php';
