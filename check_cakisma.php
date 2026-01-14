<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OlusturulanProgram;
use App\Models\Ogretmen;
use App\Models\Mekan;
use App\Models\OgrenciGrubu;
use App\Models\ZamanDilim;

echo "=== ÖĞRETMEN ÇAKIŞMALARI ===\n";
$ogretmenCakisma = OlusturulanProgram::select('ogretmen_id', 'zaman_dilimi_id')
    ->selectRaw('COUNT(*) as count')
    ->selectRaw('GROUP_CONCAT(ogrenci_grup_id) as gruplar')
    ->groupBy('ogretmen_id', 'zaman_dilimi_id')
    ->having('count', '>', 1)
    ->get();

if ($ogretmenCakisma->isEmpty()) {
    echo "Öğretmen çakışması yok ✓\n";
} else {
    foreach ($ogretmenCakisma as $c) {
        $ogretmen = Ogretmen::find($c->ogretmen_id);
        $zaman = ZamanDilim::find($c->zaman_dilimi_id);
        $grupIds = explode(',', $c->gruplar);
        $grupIsimleri = OgrenciGrubu::whereIn('id', $grupIds)->pluck('isim')->implode(', ');
        echo "ÇAKIŞMA: {$ogretmen->isim} - {$zaman->gun} {$zaman->baslangic_saat} (Gruplar: {$grupIsimleri})\n";
    }
}

echo "\n=== MEKAN ÇAKIŞMALARI ===\n";
$mekanCakisma = OlusturulanProgram::select('mekan_id', 'zaman_dilimi_id')
    ->selectRaw('COUNT(*) as count')
    ->selectRaw('GROUP_CONCAT(ogrenci_grup_id) as gruplar')
    ->groupBy('mekan_id', 'zaman_dilimi_id')
    ->having('count', '>', 1)
    ->get();

if ($mekanCakisma->isEmpty()) {
    echo "Mekan çakışması yok ✓\n";
} else {
    foreach ($mekanCakisma as $c) {
        $mekan = Mekan::find($c->mekan_id);
        $zaman = ZamanDilim::find($c->zaman_dilimi_id);
        $grupIds = explode(',', $c->gruplar);
        $grupIsimleri = OgrenciGrubu::whereIn('id', $grupIds)->pluck('isim')->implode(', ');
        echo "ÇAKIŞMA: {$mekan->isim} - {$zaman->gun} {$zaman->baslangic_saat} (Gruplar: {$grupIsimleri})\n";
    }
}

echo "\n=== GRUP ÇAKIŞMALARI (Aynı grup aynı saatte 2 ders) ===\n";
$grupCakisma = OlusturulanProgram::select('ogrenci_grup_id', 'zaman_dilimi_id')
    ->selectRaw('COUNT(*) as count')
    ->groupBy('ogrenci_grup_id', 'zaman_dilimi_id')
    ->having('count', '>', 1)
    ->get();

if ($grupCakisma->isEmpty()) {
    echo "Grup çakışması yok ✓\n";
} else {
    foreach ($grupCakisma as $c) {
        $grup = OgrenciGrubu::find($c->ogrenci_grup_id);
        $zaman = ZamanDilim::find($c->zaman_dilimi_id);
        echo "ÇAKIŞMA: {$grup->isim} - {$zaman->gun} {$zaman->baslangic_saat}\n";
    }
}

echo "\n=== ÖZET ===\n";
echo "Toplam ders sayısı: " . OlusturulanProgram::count() . "\n";
echo "Öğretmen çakışması: " . $ogretmenCakisma->count() . "\n";
echo "Mekan çakışması: " . $mekanCakisma->count() . "\n";
echo "Grup çakışması: " . $grupCakisma->count() . "\n";
