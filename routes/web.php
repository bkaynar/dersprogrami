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
use App\Http\Controllers\TimetableSettingController;
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
    Route::get('dersler/export', [DersController::class, 'export'])->name('dersler.export');
    Route::post('dersler/import', [DersController::class, 'import'])->name('dersler.import');

    // Dersler resource routes
    Route::resource('dersler', DersController::class)->parameters(['dersler' => 'ders']);

    // Ogretmenler - Excel import/export routes (önce tanımla)
    Route::get('ogretmenler/template/download', [OgretmenController::class, 'downloadTemplate'])->name('ogretmenler.template.download');
    Route::get('ogretmenler/export', [OgretmenController::class, 'export'])->name('ogretmenler.export');
    Route::post('ogretmenler/import', [OgretmenController::class, 'import'])->name('ogretmenler.import');

    // Ogretmenler resource routes
    Route::resource('ogretmenler', OgretmenController::class)->parameters(['ogretmenler' => 'ogretmen']);

    // Mekanlar - Excel import/export routes (önce tanımla)
    Route::get('mekanlar/template/download', [MekanController::class, 'downloadTemplate'])->name('mekanlar.template.download');
    Route::get('mekanlar/export', [MekanController::class, 'export'])->name('mekanlar.export');
    Route::post('mekanlar/import', [MekanController::class, 'import'])->name('mekanlar.import');
    Route::post('mekanlar/import/preview', [MekanController::class, 'importPreview'])->name('mekanlar.import.preview');
    Route::post('mekanlar/import/selected', [MekanController::class, 'importSelected'])->name('mekanlar.import.selected');

    // Ogrenci Gruplari - Excel import/export routes (önce tanımla)
    Route::get('ogrenci-gruplari/template/download', [OgrenciGrubuController::class, 'downloadTemplate'])->name('ogrenci-gruplari.template.download');
    Route::get('ogrenci-gruplari/export', [OgrenciGrubuController::class, 'export'])->name('ogrenci-gruplari.export');
    Route::post('ogrenci-gruplari/import', [OgrenciGrubuController::class, 'import'])->name('ogrenci-gruplari.import');

    // Mekanlar resource routes
    Route::resource('mekanlar', MekanController::class)->parameters(['mekanlar' => 'mekan']);

    // Ogrenci Gruplari resource routes
    Route::resource('ogrenci-gruplari', OgrenciGrubuController::class)->parameters(['ogrenci-gruplari' => 'ogrenciGrubu']);

    // Zaman Dilimleri - Excel import/export routes
    Route::get('zaman-dilimleri/template/download', [ZamanDilimController::class, 'downloadTemplate'])->name('zaman-dilimleri.template.download');
    Route::get('zaman-dilimleri/export', [ZamanDilimController::class, 'export'])->name('zaman-dilimleri.export');
    Route::post('zaman-dilimleri/import', [ZamanDilimController::class, 'import'])->name('zaman-dilimleri.import');

    // Zaman Dilimleri - Otomatik oluşturma
    Route::post('zaman-dilimleri/generate', [ZamanDilimController::class, 'generate'])->name('zaman-dilimleri.generate');

    // Zaman Dilimleri resource routes
    Route::resource('zaman-dilimleri', ZamanDilimController::class)->parameters(['zaman-dilimleri' => 'zamanDilimi']);

    // Ders Mekan Gereksinimleri - Excel import/export routes
    Route::get('ders-mekan-gereksinimleri/template/download', [DersMekanGeresinimController::class, 'downloadTemplate'])->name('ders-mekan-gereksinimleri.template.download');
    Route::get('ders-mekan-gereksinimleri/export', [DersMekanGeresinimController::class, 'export'])->name('ders-mekan-gereksinimleri.export');
    Route::post('ders-mekan-gereksinimleri/import', [DersMekanGeresinimController::class, 'import'])->name('ders-mekan-gereksinimleri.import');

    // Ders Mekan Gereksinimleri routes (ders bazlı)
    Route::get('ders-mekan-gereksinimleri', [DersMekanGeresinimController::class, 'index'])->name('ders-mekan-gereksinimleri.index');
    Route::get('ders-mekan-gereksinimleri/create', [DersMekanGeresinimController::class, 'create'])->name('ders-mekan-gereksinimleri.create');
    Route::post('ders-mekan-gereksinimleri', [DersMekanGeresinimController::class, 'store'])->name('ders-mekan-gereksinimleri.store');
    Route::get('ders-mekan-gereksinimleri/{ders}', [DersMekanGeresinimController::class, 'show'])->name('ders-mekan-gereksinimleri.show');
    Route::get('ders-mekan-gereksinimleri/{ders}/edit', [DersMekanGeresinimController::class, 'edit'])->name('ders-mekan-gereksinimleri.edit');
    Route::put('ders-mekan-gereksinimleri/{ders}', [DersMekanGeresinimController::class, 'update'])->name('ders-mekan-gereksinimleri.update');
    Route::delete('ders-mekan-gereksinimleri/{ders}', [DersMekanGeresinimController::class, 'destroy'])->name('ders-mekan-gereksinimleri.destroy');

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

    // Grup Kisitlamalari routes (grup bazlı)
    Route::get('grup-kisitlamalari', [GrupKisitlamaController::class, 'index'])->name('grup-kisitlamalari.index');
    Route::get('grup-kisitlamalari/create', [GrupKisitlamaController::class, 'create'])->name('grup-kisitlamalari.create');
    Route::post('grup-kisitlamalari', [GrupKisitlamaController::class, 'store'])->name('grup-kisitlamalari.store');
    Route::get('grup-kisitlamalari/{grup}', [GrupKisitlamaController::class, 'show'])->name('grup-kisitlamalari.show');
    Route::get('grup-kisitlamalari/{grup}/edit', [GrupKisitlamaController::class, 'edit'])->name('grup-kisitlamalari.edit');
    Route::put('grup-kisitlamalari/{grup}', [GrupKisitlamaController::class, 'update'])->name('grup-kisitlamalari.update');
    Route::delete('grup-kisitlamalari/{grup}', [GrupKisitlamaController::class, 'destroy'])->name('grup-kisitlamalari.destroy');

    // Program Olustur routes
    Route::get('program-olustur', [ProgramOlusturController::class, 'index'])->name('program-olustur.index');
    Route::post('program-olustur/generate', [ProgramOlusturController::class, 'generate'])->name('program-olustur.generate');
    Route::get('program-olustur/status', [ProgramOlusturController::class, 'status'])->name('program-olustur.status');
    Route::get('program-olustur/show', [ProgramOlusturController::class, 'show'])->name('program-olustur.show');
    Route::get('program-olustur/export/excel', [ProgramOlusturController::class, 'exportExcel'])->name('program-olustur.export.excel');
    Route::get('program-olustur/export/pdf', [ProgramOlusturController::class, 'exportPdf'])->name('program-olustur.export.pdf');
    Route::delete('program-olustur', [ProgramOlusturController::class, 'destroy'])->name('program-olustur.destroy');

    // Timetable Settings routes
    Route::get('timetable-settings', [TimetableSettingController::class, 'index'])->name('timetable-settings.index');
    Route::put('timetable-settings', [TimetableSettingController::class, 'update'])->name('timetable-settings.update');
});


require __DIR__.'/settings.php';
