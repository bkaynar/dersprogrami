<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PROGRAM KONTROL RAPORU ===" . PHP_EOL . PHP_EOL;

$programlar = \App\Models\OlusturulanProgram::with(['ogretmen', 'zamanDilim', 'ders', 'ogrenciGrubu'])->get();

if ($programlar->isEmpty()) {
    echo "❌ Henüz program oluşturulmamış!" . PHP_EOL;
    exit;
}

echo "Toplam ders sayısı: " . $programlar->count() . PHP_EOL . PHP_EOL;

// 1. Müsaitlik İhlalleri
echo "1️⃣  ÖĞRETMEN MÜSAİTLİK KONTROL" . PHP_EOL;
$musaitlikIhalleri = [];
foreach ($programlar as $p) {
    $musait = \App\Models\OgretmenMusaitlik::where('ogretmen_id', $p->ogretmen_id)
        ->where('zaman_dilimi_id', $p->zaman_dilimi_id)
        ->where('musaitlik_tipi', 'musait')
        ->exists();

    if (!$musait) {
        $musaitlikIhalleri[] = "{$p->ogretmen->isim} - {$p->ders->isim} - {$p->zamanDilim->haftanin_gunu} " . substr($p->zamanDilim->baslangic_saati, 0, 5);
    }
}

if (empty($musaitlikIhalleri)) {
    echo "   ✅ Hiç ihlal yok!" . PHP_EOL;
} else {
    echo "   ❌ " . count($musaitlikIhalleri) . " ihlal bulundu:" . PHP_EOL;
    foreach (array_slice($musaitlikIhalleri, 0, 5) as $ihlal) {
        echo "      - {$ihlal}" . PHP_EOL;
    }
    if (count($musaitlikIhalleri) > 5) {
        echo "      ... ve " . (count($musaitlikIhalleri) - 5) . " tane daha" . PHP_EOL;
    }
}

// 2. Öğretmen Çakışmaları
echo PHP_EOL . "2️⃣  ÖĞRETMEN ÇAKIŞMA KONTROL" . PHP_EOL;
$ogretmenCakisma = [];
$zamanOgretmen = [];
foreach ($programlar as $p) {
    $key = $p->zaman_dilimi_id . '-' . $p->ogretmen_id;
    if (isset($zamanOgretmen[$key])) {
        $ogretmenCakisma[] = "{$p->ogretmen->isim} - {$p->zamanDilim->haftanin_gunu} " . substr($p->zamanDilim->baslangic_saati, 0, 5);
    }
    $zamanOgretmen[$key] = true;
}

if (empty($ogretmenCakisma)) {
    echo "   ✅ Hiç çakışma yok!" . PHP_EOL;
} else {
    echo "   ❌ " . count($ogretmenCakisma) . " çakışma bulundu" . PHP_EOL;
}

// 3. Grup Çakışmaları
echo PHP_EOL . "3️⃣  GRUP ÇAKIŞMA KONTROL" . PHP_EOL;
$grupCakisma = [];
$zamanGrup = [];
foreach ($programlar as $p) {
    $key = $p->zaman_dilimi_id . '-' . $p->ogrenci_grup_id;
    if (isset($zamanGrup[$key])) {
        $grupCakisma[] = "{$p->ogrenciGrubu->isim} - {$p->zamanDilim->haftanin_gunu} " . substr($p->zamanDilim->baslangic_saati, 0, 5);
    }
    $zamanGrup[$key] = true;
}

if (empty($grupCakisma)) {
    echo "   ✅ Hiç çakışma yok!" . PHP_EOL;
} else {
    echo "   ❌ " . count($grupCakisma) . " çakışma bulundu" . PHP_EOL;
}

// 4. Mekan Çakışmaları
echo PHP_EOL . "4️⃣  MEKAN ÇAKIŞMA KONTROL" . PHP_EOL;
$mekanCakisma = [];
$zamanMekan = [];
foreach ($programlar as $p) {
    $key = $p->zaman_dilimi_id . '-' . $p->mekan_id;
    if (isset($zamanMekan[$key])) {
        $mekanCakisma[] = "Mekan ID: {$p->mekan_id} - {$p->zamanDilim->haftanin_gunu} " . substr($p->zamanDilim->baslangic_saati, 0, 5);
    }
    $zamanMekan[$key] = true;
}

if (empty($mekanCakisma)) {
    echo "   ✅ Hiç çakışma yok!" . PHP_EOL;
} else {
    echo "   ❌ " . count($mekanCakisma) . " çakışma bulundu" . PHP_EOL;
}

// 5. Ders Saat Kontrolü
echo PHP_EOL . "5️⃣  DERS SAAT KONTROL (Her dersin haftalık saati doğru mu?)" . PHP_EOL;
$dersHatalari = [];
$grupDersler = \App\Models\GrupDers::with(['ders', 'ogrenciGrubu'])->get();

foreach ($grupDersler as $gd) {
    $programdakiSaat = \App\Models\OlusturulanProgram::where('ders_id', $gd->ders_id)
        ->where('ogrenci_grup_id', $gd->ogrenci_grup_id)
        ->count();

    $olmasiGereken = $gd->ders->haftalik_saat;

    if ($programdakiSaat != $olmasiGereken) {
        $dersHatalari[] = "{$gd->ogrenciGrubu->isim} - {$gd->ders->isim}: {$programdakiSaat}/{$olmasiGereken} saat";
    }
}

if (empty($dersHatalari)) {
    echo "   ✅ Tüm dersler doğru saatte!" . PHP_EOL;
} else {
    echo "   ❌ " . count($dersHatalari) . " hata bulundu:" . PHP_EOL;
    foreach (array_slice($dersHatalari, 0, 10) as $hata) {
        echo "      - {$hata}" . PHP_EOL;
    }
    if (count($dersHatalari) > 10) {
        echo "      ... ve " . (count($dersHatalari) - 10) . " tane daha" . PHP_EOL;
    }
}

// ÖZET
echo PHP_EOL . "=== ÖZET ===" . PHP_EOL;
$toplamSorun = count($musaitlikIhalleri) + count($ogretmenCakisma) + count($grupCakisma) + count($mekanCakisma) + count($dersHatalari);

if ($toplamSorun == 0) {
    echo "🎉 MÜKEMMEL! Program tamamen geçerli, hiç sorun yok!" . PHP_EOL;
} else {
    echo "⚠️  Toplam {$toplamSorun} sorun tespit edildi." . PHP_EOL;
    echo "   - Müsaitlik ihlali: " . count($musaitlikIhalleri) . PHP_EOL;
    echo "   - Öğretmen çakışması: " . count($ogretmenCakisma) . PHP_EOL;
    echo "   - Grup çakışması: " . count($grupCakisma) . PHP_EOL;
    echo "   - Mekan çakışması: " . count($mekanCakisma) . PHP_EOL;
    echo "   - Ders saat hatası: " . count($dersHatalari) . PHP_EOL;
}
