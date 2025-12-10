<?php

namespace App\Services;

use App\Models\Ders;
use App\Models\GrupDers;
use App\Models\Mekan;
use App\Models\OgrenciGrubu;
use App\Models\Ogretmen;
use App\Models\OgretmenDers;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use App\Models\GrupKisitlama;
use App\Models\OlusturulanProgram;
use App\Models\TimetableSetting;
use Illuminate\Support\Facades\DB;

class TimetableGeneticAlgorithm
{
    // GA Parametreleri
    protected int $populationSize = 40;  // 50 -> 40 (Biraz düşürdük)
    protected int $generations = 200;    // 500 -> 200 (Optimizasyonla yeterli olacak)
    protected float $mutationRate = 0.2;
    protected float $crossoverRate = 0.8;
    protected int $eliteSize = 4;        // 5 -> 4

    // Veri setleri
    protected $zamanDilimleri;
    protected $mekanlar;
    protected $ogretmenler;
    protected $ogrenciGruplari;
    protected $dersler;
    protected $ogretmenDersler;
    protected $grupDersler;
    protected $ogretmenMusaitlikler;
    protected $grupKisitlamalar;
    protected $timetableSetting;

    // Performans için Hızlı Erişim Haritaları (Maps)
    protected array $ogretmenMusaitlikMap = [];
    protected array $grupKisitlamaMap = [];

    // Ders atamaları (her grup için) - artık blok bazında
    protected array $dersAtamalari = [];

    public function __construct()
    {
        $this->loadData();
        $this->prepareDersAtamalari();
    }

    /**
     * Veritabanından gerekli tüm verileri yükle ve map'le
     */
    protected function loadData(): void
    {
        $this->zamanDilimleri = ZamanDilim::all()->sortBy([
            ['gun_sirasi', 'asc'],
            ['baslangic_saat', 'asc']
        ])->values();
        $this->mekanlar = Mekan::all();
        $this->ogretmenler = Ogretmen::all();
        $this->ogrenciGruplari = OgrenciGrubu::all();
        $this->dersler = Ders::all();

        // İlişkileri yükle
        $this->ogretmenDersler = OgretmenDers::with(['ogretmen', 'ders'])->get();
        $this->grupDersler = GrupDers::with(['ogrenciGrubu', 'ders'])->get();
        
        // Müsaitlik ve Kısıtlamaları Map'e çevir (O(1) erişim için)
        $musaitlikler = OgretmenMusaitlik::all();
        foreach ($musaitlikler as $m) {
            // Sadece müsait olanları veya olmayanları değil, yapıyı kuralım
            // Burada veritabanında "kayıt varsa müsaittir" mantığı mı yoksa "kayıt varsa kısıtlıdır" mı önemli.
            // Projem.md'ye göre: Ogretmen_musaitlik tablosu öğretmenin müsait olduğu saatleri tutar.
            $this->ogretmenMusaitlikMap[$m->ogretmen_id][$m->zaman_dilim_id] = true;
        }

        $kisitlamalar = GrupKisitlama::all();
        foreach ($kisitlamalar as $k) {
            // Grup kısıtlaması varsa o saatte ders işlenemez
            $this->grupKisitlamaMap[$k->ogrenci_grup_id][$k->zaman_dilim_id] = true;
        }

        // Timetable ayarlarını yükle
        $this->timetableSetting = TimetableSetting::current();
    }

    /**
     * Her grup için hangi dersleri alacağını hazırla (BLOK BAZINDA)
     *
     * Örnek: 5 saatlik ders -> split_rules'a göre [3, 2] bloklar halinde
     * Her blok ayrı bir atama olarak eklenir ve kendi içinde arka arkaya olmalı
     */
    protected function prepareDersAtamalari(): void
    {
        foreach ($this->ogrenciGruplari as $grup) {
            $dersIds = $this->grupDersler
                ->where('ogrenci_grup_id', $grup->id)
                ->pluck('ders_id')
                ->toArray();

            foreach ($dersIds as $dersId) {
                $ders = $this->dersler->find($dersId);
                $haftalikSaat = $ders->haftalik_saat;

                // Ayarlardan split kuralını al
                $splitRule = $this->timetableSetting->getSplitRule($haftalikSaat);

                // Her blok için ayrı bir atama oluştur
                foreach ($splitRule as $blockIndex => $blockSize) {
                    $this->dersAtamalari[] = [
                        'grup_id' => $grup->id,
                        'ders_id' => $dersId,
                        'block_index' => $blockIndex,        // Kaçıncı blok (0, 1, 2...)
                        'block_size' => $blockSize,          // Bu blok kaç saat (2, 3, 4...)
                        'must_be_consecutive' => $this->timetableSetting->enforce_consecutive,
                    ];
                }
            }
        }
    }

    /**
     * Ana algoritma - ders programı oluştur
     */
    public function generate(): array
    {
        return $this->generateWithProgress(null);
    }

    /**
     * İlerleme callback'i ile ders programı oluştur
     */
    public function generateWithProgress(?callable $progressCallback = null): array
    {
        // PHP timeout'u artır (10 dakika)
        set_time_limit(600);

        // İlk popülasyonu oluştur
        $population = $this->createInitialPopulation();

        $bestIndividual = null;
        $bestFitness = -PHP_INT_MAX;

        for ($generation = 0; $generation < $this->generations; $generation++) {
            // Her bireyin fitness'ını hesapla
            $fitnessScores = array_map(fn($individual) => $this->calculateFitness($individual), $population);

            // En iyi bireyi bul
            $maxFitness = max($fitnessScores);
            if ($maxFitness > $bestFitness) {
                $bestFitness = $maxFitness;
                $bestIndividual = $population[array_search($maxFitness, $fitnessScores)];
            }

            // İlerlemeyi bildir
            if ($progressCallback && $generation % 5 == 0) {
                $progressCallback($generation, $this->generations, $bestFitness);
            }

            // Mükemmele ulaştıysak dur (0 puan = cezasız = çakışmasız)
            if ($bestFitness >= 0) {
                break;
            }

            // Yeni nesil oluştur
            $population = $this->evolvePopulation($population, $fitnessScores);
        }

        // Son ilerlemeyi bildir
        if ($progressCallback) {
            $progressCallback($generation + 1, $this->generations, $bestFitness);
        }

        return [
            'schedule' => $bestIndividual,
            'fitness' => $bestFitness,
            'generations' => $generation + 1,
        ];
    }

    /**
     * İlk popülasyonu rastgele oluştur
     */
    protected function createInitialPopulation(): array
    {
        $population = [];

        for ($i = 0; $i < $this->populationSize; $i++) {
            $population[] = $this->createRandomIndividual();
        }

        return $population;
    }

    /**
     * Rastgele bir birey (ders programı) oluştur
     */
    protected function createRandomIndividual(): array
    {
        $individual = [];

        foreach ($this->dersAtamalari as $atama) {
            $blockSize = $atama['block_size'];

            // Aynı gün içinde blockSize kadar arka arkaya slot bulalım
            $validBlocks = $this->findConsecutiveSlots($blockSize);

            if (empty($validBlocks)) {
                // Uygun blok bulunamadıysa rastgele dağıt (fitness düşük olacak)
                $selectedSlots = [];
                for ($i = 0; $i < $blockSize; $i++) {
                    $selectedSlots[] = $this->zamanDilimleri->random();
                }
            } else {
                // Rastgele bir geçerli blok seç
                $selectedSlots = $validBlocks[array_rand($validBlocks)];
            }

            // Rastgele mekan seç
            $mekan = $this->mekanlar->random();

            // Bu ders için uygun öğretmenleri bul
            $uygunOgretmenler = $this->ogretmenDersler
                ->where('ders_id', $atama['ders_id'])
                ->pluck('ogretmen_id')
                ->toArray();

            if (empty($uygunOgretmenler)) {
                continue;
            }

            $ogretmenId = $uygunOgretmenler[array_rand($uygunOgretmenler)];

            // Blok için arka arkaya zaman dilimleri oluştur
            for ($i = 0; $i < $blockSize; $i++) {
                $zamanDilim = $selectedSlots[$i];

                $individual[] = [
                    'grup_id' => $atama['grup_id'],
                    'ders_id' => $atama['ders_id'],
                    'block_index' => $atama['block_index'],
                    'block_size' => $blockSize,
                    'slot_in_block' => $i,  // Bu slot bloktaki kaçıncı saat (0, 1, 2...)
                    'ogretmen_id' => $ogretmenId,
                    'zaman_dilim_id' => $zamanDilim->id,
                    'mekan_id' => $mekan->id,
                ];
            }
        }

        return $individual;
    }

    /**
     * Belirli bir blok büyüklüğü için aynı gün içinde arka arkaya slot kombinasyonları bul
     */
    protected function findConsecutiveSlots(int $blockSize): array
    {
        $validBlocks = [];

        // Günlere göre grupla
        $byDay = $this->zamanDilimleri->groupBy('haftanin_gunu');

        foreach ($byDay as $gun => $slots) {
            // Bu gündeki slotları sırala
            $sortedSlots = $slots->sortBy('baslangic_saati')->values();

            // Arka arkaya blockSize kadar slot ara
            for ($i = 0; $i <= $sortedSlots->count() - $blockSize; $i++) {
                $block = [];
                $isConsecutive = true;

                for ($j = 0; $j < $blockSize; $j++) {
                    if ($i + $j >= $sortedSlots->count()) {
                        $isConsecutive = false;
                        break;
                    }

                    $currentSlot = $sortedSlots[$i + $j];
                    $block[] = $currentSlot;

                    // İlk slot değilse, bir önceki ile arka arkaya mı kontrol et
                    if ($j > 0) {
                        $prevSlot = $sortedSlots[$i + $j - 1];

                        // Bitiş saati = Başlangıç saati kontrolü (veya çok yakın)
                        $prevBitis = strtotime($prevSlot->bitis_saati);
                        $currBaslangic = strtotime($currentSlot->baslangic_saati);

                        // Maksimum 15 dakika fark olabilir (teneffüs)
                        if (abs($currBaslangic - $prevBitis) > 900) {
                            $isConsecutive = false;
                            break;
                        }
                    }
                }

                if ($isConsecutive && count($block) === $blockSize) {
                    $validBlocks[] = $block;
                }
            }
        }

        return $validBlocks;
    }

    /**
     * Fitness fonksiyonu - programı puanla
     * Pozitif puan = geçerli program
     * Negatif puan = ihlal sayısı
     */
    protected function calculateFitness(array $individual): float
    {
        $score = 0;

        // SERT KISITLAR (CEZALARI ÇOK ARTIRDIM - Çakışmasızlık Garantisi İçin)

        // 1. Aynı zaman diliminde aynı öğretmen farklı yerlerde olamaz
        $score -= $this->checkOgretmenCakismasi($individual) * 100000;

        // 2. Aynı zaman diliminde aynı grup farklı derslerde olamaz
        $score -= $this->checkGrupCakismasi($individual) * 100000;

        // 3. Aynı zaman diliminde aynı mekan birden fazla derse ayrılamaz
        $score -= $this->checkMekanCakismasi($individual) * 100000;

        // 4. Öğretmen müsaitliği kontrolü
        $score -= $this->checkOgretmenMusaitlik($individual) * 50000;

        // 5. Grup kısıtlamaları kontrolü
        $score -= $this->checkGrupKisitlama($individual) * 50000;

        // 6. Blokların arka arkaya olması kontrolü (SERT KISIT)
        $score -= $this->checkBlockConsecutiveness($individual) * 50000;

        // 7. Aynı dersin farklı blokları aynı gün OLMAMALI (SERT KISIT)
        $score -= $this->checkSameDayCourseDistribution($individual) * 50000;

        // YUMUŞAK KISITLAR (tercihe dayalı)

        // 6. Aynı günde derslerin toplu olması tercih edilir (boşlukları azalt)
        $score += $this->calculateCompactnessScore($individual) * 10;

        // 7. Öğretmenlerin derslerinin de toplu olması tercih edilir
        $score += $this->calculateOgretmenCompactnessScore($individual) * 8;

        // 8. Aynı dersin saatlerinin haftaya dengeli dağılması
        $score += $this->calculateDersDistributionScore($individual) * 5;

        return $score;
    }

    /**
     * Öğretmen çakışması kontrolü
     */
    protected function checkOgretmenCakismasi(array $individual): int
    {
        $violations = 0;
        $zamanOgretmen = [];

        foreach ($individual as $slot) {
            $key = $slot['zaman_dilim_id'] . '-' . $slot['ogretmen_id'];
            if (isset($zamanOgretmen[$key])) {
                $violations++;
            }
            $zamanOgretmen[$key] = true;
        }

        return $violations;
    }

    /**
     * Grup çakışması kontrolü
     */
    protected function checkGrupCakismasi(array $individual): int
    {
        $violations = 0;
        $zamanGrup = [];

        foreach ($individual as $slot) {
            $key = $slot['zaman_dilim_id'] . '-' . $slot['grup_id'];
            if (isset($zamanGrup[$key])) {
                $violations++;
            }
            $zamanGrup[$key] = true;
        }

        return $violations;
    }

    /**
     * Mekan çakışması kontrolü
     */
    protected function checkMekanCakismasi(array $individual): int
    {
        $violations = 0;
        $zamanMekan = [];

        foreach ($individual as $slot) {
            $key = $slot['zaman_dilim_id'] . '-' . $slot['mekan_id'];
            if (isset($zamanMekan[$key])) {
                $violations++;
            }
            $zamanMekan[$key] = true;
        }

        return $violations;
    }

    /**
     * Öğretmen müsaitliği kontrolü (Hızlandırılmış)
     */
    protected function checkOgretmenMusaitlik(array $individual): int
    {
        $violations = 0;

        foreach ($individual as $slot) {
            $ogretmenId = $slot['ogretmen_id'];
            $zamanId = $slot['zaman_dilim_id'];

            // Map'ten kontrol et (Eğer map'te set edilmemişse öğretmen o saatte müsait değil demektir)
            // Varsayım: ogretmen_musaitlik tablosu sadece MÜSAİT olan zamanları tutuyor.
            if (!isset($this->ogretmenMusaitlikMap[$ogretmenId][$zamanId])) {
                $violations++;
            }
        }

        return $violations;
    }

    /**
     * Grup kısıtlamaları kontrolü (Hızlandırılmış)
     */
    protected function checkGrupKisitlama(array $individual): int
    {
        $violations = 0;

        foreach ($individual as $slot) {
            $grupId = $slot['grup_id'];
            $zamanId = $slot['zaman_dilim_id'];

            // Map'ten kontrol et (Eğer map'te varsa, kısıtlama var demektir)
            if (isset($this->grupKisitlamaMap[$grupId][$zamanId])) {
                $violations++;
            }
        }

        return $violations;
    }

    /**
     * Derslerin topluluk skoru (boşlukları minimize et)
     * Her grup için günlük ders dağılımını analiz eder ve:
     * - Derslerin arka arkaya olmasını ödüllendirir
     * - Aralarında boşluk olmasını cezalandırır
     */
    protected function calculateCompactnessScore(array $individual): float
    {
        $score = 0;

        // Her grup için ayrı analiz yap
        foreach ($this->ogrenciGruplari as $grup) {
            $grupDersleri = array_filter($individual, fn($slot) => $slot['grup_id'] == $grup->id);

            // Günlere göre grupla
            $gunlereGore = [];
            foreach ($grupDersleri as $ders) {
                $zamanDilim = $this->zamanDilimleri->find($ders['zaman_dilim_id']);
                $gun = $zamanDilim->gun_sirasi;

                if (!isset($gunlereGore[$gun])) {
                    $gunlereGore[$gun] = [];
                }
                $gunlereGore[$gun][] = $zamanDilim->id;
            }

            // Her gün için topluluk analizi
            foreach ($gunlereGore as $gun => $zamanDilimIds) {
                if (count($zamanDilimIds) <= 1) {
                    continue; // Tek ders varsa kontrol gerek yok
                }

                // Zaman dilimlerini sırala
                $zamanDilimleri = $this->zamanDilimleri
                    ->whereIn('id', $zamanDilimIds)
                    ->sortBy('baslangic_saat')
                    ->values();

                // Ardışık dersleri kontrol et
                for ($i = 0; $i < count($zamanDilimleri) - 1; $i++) {
                    $current = $zamanDilimleri[$i];
                    $next = $zamanDilimleri[$i + 1];

                    // Eğer dersler arka arkaysa (bir sonraki zaman dilimi hemen başlıyorsa)
                    if ($current->bitis_saat === $next->baslangic_saat) {
                        $score += 5; // Arka arkaya dersler için ödül
                    } else {
                        // Arada boşluk var, ceza ver
                        $score -= 3;
                    }
                }

                // Günde çok fazla ders varsa küçük bir ceza (aşırı yoğunluktan kaçınmak için)
                if (count($zamanDilimIds) > 6) {
                    $score -= 2;
                }
            }

            // Haftaya dengeli dağıtım kontrolü
            $gunSayisi = count($gunlereGore);
            if ($gunSayisi > 0) {
                $ortalamaGunlukDers = count($grupDersleri) / 5; // 5 iş günü varsayımı

                // Her gün için ideal dağılıma yakınlık
                foreach ($gunlereGore as $gun => $zamanDilimIds) {
                    $fark = abs(count($zamanDilimIds) - $ortalamaGunlukDers);
                    $score -= $fark * 0.5; // Dengesizlik cezası
                }
            }
        }

        return $score;
    }

    /**
     * Öğretmenlerin derslerinin topluluk skoru
     * Öğretmenlerin derslerinin de blok halinde olmasını teşvik eder
     */
    protected function calculateOgretmenCompactnessScore(array $individual): float
    {
        $score = 0;

        // Her öğretmen için ayrı analiz yap
        foreach ($this->ogretmenler as $ogretmen) {
            $ogretmenDersleri = array_filter($individual, fn($slot) => $slot['ogretmen_id'] == $ogretmen->id);

            if (count($ogretmenDersleri) <= 1) {
                continue;
            }

            // Günlere göre grupla
            $gunlereGore = [];
            foreach ($ogretmenDersleri as $ders) {
                $zamanDilim = $this->zamanDilimleri->find($ders['zaman_dilim_id']);
                $gun = $zamanDilim->gun_sirasi;

                if (!isset($gunlereGore[$gun])) {
                    $gunlereGore[$gun] = [];
                }
                $gunlereGore[$gun][] = $zamanDilim->id;
            }

            // Her gün için topluluk analizi
            foreach ($gunlereGore as $gun => $zamanDilimIds) {
                if (count($zamanDilimIds) <= 1) {
                    continue;
                }

                // Zaman dilimlerini sırala
                $zamanDilimleri = $this->zamanDilimleri
                    ->whereIn('id', $zamanDilimIds)
                    ->sortBy('baslangic_saat')
                    ->values();

                // Ardışık dersleri kontrol et
                for ($i = 0; $i < count($zamanDilimleri) - 1; $i++) {
                    $current = $zamanDilimleri[$i];
                    $next = $zamanDilimleri[$i + 1];

                    if ($current->bitis_saat === $next->baslangic_saat) {
                        $score += 3; // Arka arkaya dersler için ödül
                    } else {
                        $score -= 2; // Boşluk için ceza
                    }
                }
            }

            // Öğretmenin haftaya dengeli dağılımı
            $gunSayisi = count($gunlereGore);
            if ($gunSayisi > 0 && $gunSayisi < 5) {
                // Derslerin az güne sıkışması tercih edilir (öğretmen için)
                $score += (5 - $gunSayisi) * 2;
            }
        }

        return $score;
    }

    /**
     * Ders saatlerinin haftaya dengeli dağılım skoru
     * Aynı dersin saatlerinin haftanın farklı günlerine yayılmasını teşvik eder
     */
    protected function calculateDersDistributionScore(array $individual): float
    {
        $score = 0;

        // Her grup-ders kombinasyonu için analiz
        foreach ($this->grupDersler as $grupDers) {
            $slotlar = array_filter($individual, function ($slot) use ($grupDers) {
                return $slot['grup_id'] == $grupDers->ogrenci_grup_id
                    && $slot['ders_id'] == $grupDers->ders_id;
            });

            if (count($slotlar) <= 1) {
                continue;
            }

            // Bu dersin günlere dağılımını kontrol et
            $gunler = [];
            foreach ($slotlar as $slot) {
                $zamanDilim = $this->zamanDilimleri->find($slot['zaman_dilim_id']);
                $gunler[$zamanDilim->gun_sirasi] = true;
            }

            $farkliGunSayisi = count($gunler);
            $toplamSlot = count($slotlar);

            // Derslerin farklı günlere yayılması tercih edilir
            if ($farkliGunSayisi == $toplamSlot) {
                // Her ders farklı günde - mükemmel
                $score += 10;
            } elseif ($farkliGunSayisi >= $toplamSlot / 2) {
                // İyi dağılım
                $score += 5;
            } else {
                // Derslerin aynı günlerde toplanması - ceza
                $score -= 3;
            }
        }

        return $score;
    }

    /**
     * Yeni nesil oluştur (evrim)
     */
    protected function evolvePopulation(array $population, array $fitnessScores): array
    {
        $newPopulation = [];

        // Elit bireyleri koru
        $elites = $this->selectElites($population, $fitnessScores);
        $newPopulation = array_merge($newPopulation, $elites);

        // Geri kalanı oluştur
        while (count($newPopulation) < $this->populationSize) {
            // Seçilim
            $parent1 = $this->selectParent($population, $fitnessScores);
            $parent2 = $this->selectParent($population, $fitnessScores);

            // Çaprazlama
            if (rand(0, 100) / 100 < $this->crossoverRate) {
                [$child1, $child2] = $this->crossover($parent1, $parent2);
            } else {
                $child1 = $parent1;
                $child2 = $parent2;
            }

            // Mutasyon
            if (rand(0, 100) / 100 < $this->mutationRate) {
                $child1 = $this->mutate($child1);
            }
            if (rand(0, 100) / 100 < $this->mutationRate) {
                $child2 = $this->mutate($child2);
            }

            $newPopulation[] = $child1;
            if (count($newPopulation) < $this->populationSize) {
                $newPopulation[] = $child2;
            }
        }

        return $newPopulation;
    }

    /**
     * Elit bireyleri seç
     */
    protected function selectElites(array $population, array $fitnessScores): array
    {
        $combined = array_map(null, $population, $fitnessScores);
        usort($combined, fn($a, $b) => $b[1] <=> $a[1]);

        return array_slice(array_column($combined, 0), 0, $this->eliteSize);
    }

    /**
     * Ebeveyn seç (Tournament Selection)
     */
    protected function selectParent(array $population, array $fitnessScores): array
    {
        $tournamentSize = 5;
        $best = null;
        $bestFitness = -PHP_INT_MAX;

        for ($i = 0; $i < $tournamentSize; $i++) {
            $index = rand(0, count($population) - 1);
            if ($fitnessScores[$index] > $bestFitness) {
                $bestFitness = $fitnessScores[$index];
                $best = $population[$index];
            }
        }

        return $best;
    }

    /**
     * Çaprazlama (One-Point Crossover)
     */
    protected function crossover(array $parent1, array $parent2): array
    {
        // Blok bazlı crossover: Her bloğu bütün halde tut
        // Her ebeveynden rastgele bloklar seç

        $child1 = [];
        $child2 = [];

        // Parent1'deki blokları grupla
        $blocks1 = $this->groupByBlocks($parent1);
        $blocks2 = $this->groupByBlocks($parent2);

        $allBlockKeys = array_unique(array_merge(array_keys($blocks1), array_keys($blocks2)));

        foreach ($allBlockKeys as $blockKey) {
            // %50 ihtimalle parent1'den, %50 parent2'den al
            if (rand(0, 1) === 0) {
                if (isset($blocks1[$blockKey])) {
                    $child1 = array_merge($child1, $blocks1[$blockKey]);
                }
                if (isset($blocks2[$blockKey])) {
                    $child2 = array_merge($child2, $blocks2[$blockKey]);
                }
            } else {
                if (isset($blocks2[$blockKey])) {
                    $child1 = array_merge($child1, $blocks2[$blockKey]);
                }
                if (isset($blocks1[$blockKey])) {
                    $child2 = array_merge($child2, $blocks1[$blockKey]);
                }
            }
        }

        return [$child1, $child2];
    }

    /**
     * Individual'ı bloklara göre grupla
     */
    protected function groupByBlocks(array $individual): array
    {
        $blocks = [];

        foreach ($individual as $slot) {
            $blockKey = $slot['grup_id'] . '-' . $slot['ders_id'] . '-' . $slot['block_index'];

            if (!isset($blocks[$blockKey])) {
                $blocks[$blockKey] = [];
            }

            $blocks[$blockKey][] = $slot;
        }

        return $blocks;
    }

    /**
     * Mutasyon
     */
    protected function mutate(array $individual): array
    {
        if (empty($individual)) {
            return $individual;
        }

        // Rastgele bir slot seç
        $randomIndex = rand(0, count($individual) - 1);
        $randomSlot = $individual[$randomIndex];

        // Bu slotun bloğunu bul (aynı grup_id, ders_id, block_index olan tüm slotlar)
        $blockKey = $randomSlot['grup_id'] . '-' . $randomSlot['ders_id'] . '-' . $randomSlot['block_index'];
        $blockIndices = [];

        foreach ($individual as $idx => $slot) {
            $slotBlockKey = $slot['grup_id'] . '-' . $slot['ders_id'] . '-' . $slot['block_index'];
            if ($slotBlockKey === $blockKey) {
                $blockIndices[] = $idx;
            }
        }

        // Rastgele bir özelliği değiştir
        $mutationType = rand(0, 2);

        switch ($mutationType) {
            case 0: // Zaman dilimlerini değiştir (TÜM BLOK için arka arkaya)
                $blockSize = count($blockIndices);
                $validBlocks = $this->findConsecutiveSlots($blockSize);

                if (!empty($validBlocks)) {
                    $newSlots = $validBlocks[array_rand($validBlocks)];

                    foreach ($blockIndices as $i => $blockIdx) {
                        if (isset($newSlots[$i])) {
                            $individual[$blockIdx]['zaman_dilim_id'] = $newSlots[$i]->id;
                        }
                    }
                }
                break;

            case 1: // Mekan değiştir (TÜM BLOK için aynı mekan)
                $newMekan = $this->mekanlar->random();
                foreach ($blockIndices as $blockIdx) {
                    $individual[$blockIdx]['mekan_id'] = $newMekan->id;
                }
                break;

            case 2: // Öğretmen değiştir (TÜM BLOK için aynı öğretmen)
                $uygunOgretmenler = $this->ogretmenDersler
                    ->where('ders_id', $randomSlot['ders_id'])
                    ->pluck('ogretmen_id')
                    ->toArray();

                if (!empty($uygunOgretmenler)) {
                    $newOgretmen = $uygunOgretmenler[array_rand($uygunOgretmenler)];
                    foreach ($blockIndices as $blockIdx) {
                        $individual[$blockIdx]['ogretmen_id'] = $newOgretmen;
                    }
                }
                break;
        }

        return $individual;
    }

    /**
     * Programı veritabanına kaydet
     *
     * Blok mantığı: Aynı bloktaki tüm slotlar kaydedilmeli.
     * Çakışma kontrolü: Bir zaman diliminde sadece 1 öğretmen/grup/mekan olmalı.
     */
    public function saveSchedule(array $schedule): void
    {
        $savedCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($schedule, &$savedCount, &$skippedCount) {
            // Mevcut programları temizle
            OlusturulanProgram::query()->delete();

            // Tüm slotları kaydet - çakışma kontrolü fitness fonksiyonunda yapılıyor
            // Burada sadece veritabanına yazıyoruz
            foreach ($schedule as $slot) {
                try {
                    OlusturulanProgram::create([
                        'akademik_donem' => '2024-2025 Güz',
                        'ogrenci_grup_id' => $slot['grup_id'],
                        'ders_id' => $slot['ders_id'],
                        'ogretmen_id' => $slot['ogretmen_id'],
                        'zaman_dilimi_id' => $slot['zaman_dilim_id'],
                        'mekan_id' => $slot['mekan_id'],
                    ]);
                    $savedCount++;
                } catch (\Exception $e) {
                    $skippedCount++;
                    \Log::error("Slot kaydetme hatası (DersID: {$slot['ders_id']}, GrupID: {$slot['grup_id']}): " . $e->getMessage());
                }
            }
        });

        // İstatistikleri logla (transaction dışında)
        \Log::info("Program kaydedildi: {$savedCount} ders, {$skippedCount} hata atlandı");
    }

    /**
     * Blokların arka arkaya olup olmadığını kontrol et
     *
     * Bir dersin aynı bloğundaki tüm slotlar, arka arkaya zaman dilimlerinde olmalı
     */
    protected function checkBlockConsecutiveness(array $individual): int
    {
        $violations = 0;

        // Grup+Ders+Block bazında slotları grupla
        $blocks = [];
        foreach ($individual as $slot) {
            $blockKey = $slot['grup_id'] . '-' . $slot['ders_id'] . '-' . $slot['block_index'];

            if (!isset($blocks[$blockKey])) {
                $blocks[$blockKey] = [];
            }

            $blocks[$blockKey][] = $slot;
        }

        // Her blok için arka arkaya kontrolü
        foreach ($blocks as $blockKey => $slots) {
            // Block_size kontrolü
            $expectedSize = $slots[0]['block_size'];
            if (count($slots) !== $expectedSize) {
                $violations += abs(count($slots) - $expectedSize);
                continue;
            }

            // Zaman dilimlerini sırala
            usort($slots, function($a, $b) {
                return $a['zaman_dilim_id'] <=> $b['zaman_dilim_id'];
            });

            // Arka arkaya mı kontrol et
            for ($i = 1; $i < count($slots); $i++) {
                $prevZamanDilim = $this->zamanDilimleri->firstWhere('id', $slots[$i - 1]['zaman_dilim_id']);
                $currZamanDilim = $this->zamanDilimleri->firstWhere('id', $slots[$i]['zaman_dilim_id']);

                // Aynı gün mü?
                if ($prevZamanDilim->haftanin_gunu !== $currZamanDilim->haftanin_gunu) {
                    $violations++;
                    continue;
                }

                // Index olarak arka arkaya mı?
                $prevIndex = $this->zamanDilimleri->search(function($item) use ($prevZamanDilim) {
                    return $item->id === $prevZamanDilim->id;
                });

                $currIndex = $this->zamanDilimleri->search(function($item) use ($currZamanDilim) {
                    return $item->id === $currZamanDilim->id;
                });

                if ($currIndex !== $prevIndex + 1) {
                    $violations++;
                }
            }

            // Aynı blokta tüm slotlar aynı öğretmen ve mekanda mı?
            $firstOgretmen = $slots[0]['ogretmen_id'];
            $firstMekan = $slots[0]['mekan_id'];

            foreach ($slots as $slot) {
                if ($slot['ogretmen_id'] !== $firstOgretmen || $slot['mekan_id'] !== $firstMekan) {
                    $violations++;
                }
            }
        }

        return $violations;
    }

    /**
     * Aynı dersin farklı bloklarının aynı gün OLMAMASI kontrolü
     * Örn: 4 saatlik ders (2+2) -> Bu iki blok farklı günlerde olmalı
     */
    protected function checkSameDayCourseDistribution(array $individual): int
    {
        $violations = 0;

        // Grup+Ders bazında slotları grupla
        $courseSlots = [];
        foreach ($individual as $slot) {
            $key = $slot['grup_id'] . '-' . $slot['ders_id'];

            if (!isset($courseSlots[$key])) {
                $courseSlots[$key] = [];
            }
            $courseSlots[$key][] = $slot;
        }

        foreach ($courseSlots as $key => $slots) {
            // Blok indexlerini bul
            $blockIndices = array_unique(array_column($slots, 'block_index'));

            // Eğer ders tek bloktan oluşuyorsa sorun yok
            if (count($blockIndices) <= 1) {
                continue;
            }

            // Her bloğun hangi günde olduğunu bul
            $blockDays = [];
            foreach ($slots as $slot) {
                $blockIndex = $slot['block_index'];
                
                // Bu bloğun gününü daha önce bulmadıysak bul
                if (!isset($blockDays[$blockIndex])) {
                    $zamanDilim = $this->zamanDilimleri->firstWhere('id', $slot['zaman_dilim_id']);
                    $blockDays[$blockIndex] = $zamanDilim->haftanin_gunu;
                }
            }

            // Günlerin benzersiz olup olmadığını kontrol et
            $days = array_values($blockDays);
            $uniqueDays = array_unique($days);

            // Eğer gün sayısı, blok sayısından azsa -> çakışma var demektir
            if (count($days) !== count($uniqueDays)) {
                // Kaç tane çakışma varsa o kadar ceza
                $violations += (count($days) - count($uniqueDays));
            }
        }

        return $violations;
    }
}
