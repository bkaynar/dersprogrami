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
use Illuminate\Support\Facades\DB;

class TimetableGeneticAlgorithm
{
    // GA Parametreleri
    protected int $populationSize = 50;
    protected int $generations = 100;
    protected float $mutationRate = 0.2;
    protected float $crossoverRate = 0.8;
    protected int $eliteSize = 5;

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

    // Ders atamaları (her grup için)
    protected array $dersAtamalari = [];

    public function __construct()
    {
        $this->loadData();
        $this->prepareDersAtamalari();
    }

    /**
     * Veritabanından gerekli tüm verileri yükle
     */
    protected function loadData(): void
    {
        $this->zamanDilimleri = ZamanDilim::orderBy('gun_sirasi')->orderBy('baslangic_saat')->get();
        $this->mekanlar = Mekan::all();
        $this->ogretmenler = Ogretmen::all();
        $this->ogrenciGruplari = OgrenciGrubu::all();
        $this->dersler = Ders::all();

        // İlişkileri yükle
        $this->ogretmenDersler = OgretmenDers::with(['ogretmen', 'ders'])->get();
        $this->grupDersler = GrupDers::with(['ogrenciGrubu', 'ders'])->get();
        $this->ogretmenMusaitlikler = OgretmenMusaitlik::all();
        $this->grupKisitlamalar = GrupKisitlama::all();
    }

    /**
     * Her grup için hangi dersleri alacağını hazırla
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

                // Dersin haftalık saati kadar slot oluştur
                for ($i = 0; $i < $ders->haftalik_saat; $i++) {
                    $this->dersAtamalari[] = [
                        'grup_id' => $grup->id,
                        'ders_id' => $dersId,
                        'slot_index' => $i,
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

            // Mükemmele ulaştıysak dur
            if ($bestFitness >= 0) {
                break;
            }

            // Yeni nesil oluştur
            $population = $this->evolvePopulation($population, $fitnessScores);
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
            // Rastgele zaman dilimi seç
            $zamanDilim = $this->zamanDilimleri->random();

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

            $individual[] = [
                'grup_id' => $atama['grup_id'],
                'ders_id' => $atama['ders_id'],
                'ogretmen_id' => $ogretmenId,
                'zaman_dilim_id' => $zamanDilim->id,
                'mekan_id' => $mekan->id,
            ];
        }

        return $individual;
    }

    /**
     * Fitness fonksiyonu - programı puanla
     * Pozitif puan = geçerli program
     * Negatif puan = ihlal sayısı
     */
    protected function calculateFitness(array $individual): float
    {
        $score = 0;

        // SERT KISITLAR (ihlal ederse büyük ceza)

        // 1. Aynı zaman diliminde aynı öğretmen farklı yerlerde olamaz
        $score -= $this->checkOgretmenCakismasi($individual) * 1000;

        // 2. Aynı zaman diliminde aynı grup farklı derslerde olamaz
        $score -= $this->checkGrupCakismasi($individual) * 1000;

        // 3. Aynı zaman diliminde aynı mekan birden fazla derse ayrılamaz
        $score -= $this->checkMekanCakismasi($individual) * 1000;

        // 4. Öğretmen müsaitliği kontrolü
        $score -= $this->checkOgretmenMusaitlik($individual) * 500;

        // 5. Grup kısıtlamaları kontrolü
        $score -= $this->checkGrupKisitlama($individual) * 500;

        // YUMUŞAK KISITLAR (tercihe dayalı)

        // 6. Aynı günde derslerin toplu olması tercih edilir (boşlukları azalt)
        $score += $this->calculateCompactnessScore($individual) * 10;

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
     * Öğretmen müsaitliği kontrolü
     */
    protected function checkOgretmenMusaitlik(array $individual): int
    {
        $violations = 0;

        foreach ($individual as $slot) {
            $zamanDilim = $this->zamanDilimleri->find($slot['zaman_dilim_id']);

            $musaitMi = $this->ogretmenMusaitlikler
                ->where('ogretmen_id', $slot['ogretmen_id'])
                ->where('zaman_dilim_id', $zamanDilim->id)
                ->first();

            if (!$musaitMi) {
                $violations++;
            }
        }

        return $violations;
    }

    /**
     * Grup kısıtlamaları kontrolü
     */
    protected function checkGrupKisitlama(array $individual): int
    {
        $violations = 0;

        foreach ($individual as $slot) {
            $zamanDilim = $this->zamanDilimleri->find($slot['zaman_dilim_id']);

            $kisitliMi = $this->grupKisitlamalar
                ->where('ogrenci_grup_id', $slot['grup_id'])
                ->where('zaman_dilim_id', $zamanDilim->id)
                ->first();

            if ($kisitliMi) {
                $violations++;
            }
        }

        return $violations;
    }

    /**
     * Derslerin topluluk skoru (boşlukları minimize et)
     */
    protected function calculateCompactnessScore(array $individual): float
    {
        // Her grup için günlük ders dağılımını hesapla
        $score = 0;

        // Basitleştirilmiş: şimdilik 0 dön, sonra geliştirebiliriz
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
        $point = rand(1, min(count($parent1), count($parent2)) - 1);

        $child1 = array_merge(
            array_slice($parent1, 0, $point),
            array_slice($parent2, $point)
        );

        $child2 = array_merge(
            array_slice($parent2, 0, $point),
            array_slice($parent1, $point)
        );

        return [$child1, $child2];
    }

    /**
     * Mutasyon
     */
    protected function mutate(array $individual): array
    {
        if (empty($individual)) {
            return $individual;
        }

        $index = rand(0, count($individual) - 1);

        // Rastgele bir özelliği değiştir
        $mutationType = rand(0, 2);

        switch ($mutationType) {
            case 0: // Zaman dilimi değiştir
                $individual[$index]['zaman_dilim_id'] = $this->zamanDilimleri->random()->id;
                break;
            case 1: // Mekan değiştir
                $individual[$index]['mekan_id'] = $this->mekanlar->random()->id;
                break;
            case 2: // Öğretmen değiştir (eğer uygunsa)
                $uygunOgretmenler = $this->ogretmenDersler
                    ->where('ders_id', $individual[$index]['ders_id'])
                    ->pluck('ogretmen_id')
                    ->toArray();
                if (!empty($uygunOgretmenler)) {
                    $individual[$index]['ogretmen_id'] = $uygunOgretmenler[array_rand($uygunOgretmenler)];
                }
                break;
        }

        return $individual;
    }

    /**
     * Programı veritabanına kaydet
     */
    public function saveSchedule(array $schedule): void
    {
        DB::transaction(function () use ($schedule) {
            // Mevcut programları temizle
            OlusturulanProgram::truncate();

            // Yeni programı kaydet
            foreach ($schedule as $slot) {
                OlusturulanProgram::create([
                    'ogrenci_grup_id' => $slot['grup_id'],
                    'ders_id' => $slot['ders_id'],
                    'ogretmen_id' => $slot['ogretmen_id'],
                    'zaman_dilim_id' => $slot['zaman_dilim_id'],
                    'mekan_id' => $slot['mekan_id'],
                ]);
            }
        });
    }
}
