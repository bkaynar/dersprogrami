<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$sorunluOgretmenler = ['Aleyna GÜRSOY KAYGIN', 'Mustafa Yağcı', 'Ayla KAYABAŞ'];

foreach ($sorunluOgretmenler as $isim) {
    $ogretmen = \App\Models\Ogretmen::where('isim', 'like', "%{$isim}%")->first();
    if (!$ogretmen) continue;

    // İhtiyaç
    $ogretmenDersler = \App\Models\OgretmenDers::where('ogretmen_id', $ogretmen->id)->get();
    $toplamSaat = 0;
    foreach ($ogretmenDersler as $od) {
        $grupSayisi = \App\Models\GrupDers::where('ders_id', $od->ders_id)->count();
        $toplamSaat += $od->ders->haftalik_saat * $grupSayisi;
    }

    // Müsait
    $musaitSaat = \App\Models\OgretmenMusaitlik::where('ogretmen_id', $ogretmen->id)
        ->where('musaitlik_tipi', 'musait')
        ->count();

    $durum = $toplamSaat <= $musaitSaat ? '✓' : '✗';
    echo "{$durum} {$ogretmen->isim}: {$toplamSaat} saat ihtiyaç / {$musaitSaat} saat müsait" . PHP_EOL;
}
