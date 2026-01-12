<?php

namespace App\Services;

use App\Models\Ders;
use App\Models\DersMekanGereksinimi;
use App\Models\GrupDers;
use App\Models\GrupKisitlama;
use App\Models\Mekan;
use App\Models\OgrenciGrubu;
use App\Models\Ogretmen;
use App\Models\OgretmenDers;
use App\Models\OgretmenMusaitlik;
use App\Models\OlusturulanProgram;
use App\Models\TimetableSetting;
use App\Models\ZamanDilim;
use Illuminate\Support\Facades\DB;

class TimetableGeneticAlgorithm
{
    // GA Parametreleri (Daha kapsamlı ve kaliteli sonuç için)
    protected int $populationSize = 150;   // Daha fazla çeşitlilik
    protected int $generations = 800;       // Daha fazla iterasyon
    protected float $mutationRate = 0.3;    // Daha fazla mutasyon
    protected float $crossoverRate = 0.9;   // Yüksek crossover
    protected int $eliteSize = 15;          // En iyileri koru

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

    protected $dersMekanGereksinimleri;

    // Performans için Hızlı Erişim Haritaları (Maps)
    protected array $ogretmenMusaitlikMap = [];
    protected array $grupKisitlamaMap = [];
    protected array $dersMekanMap = [];
    protected array $mekanTipiMap = [];
    protected array $grupKapasiteMap = [];
    protected array $zamanDilimleriById = []; // ID'ye göre hızlı erişim

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
            ['baslangic_saati', 'asc'],
        ])->values();
        $this->mekanlar = Mekan::all();
        $this->ogretmenler = Ogretmen::all();
        $this->ogrenciGruplari = OgrenciGrubu::all();
        $this->dersler = Ders::all();

        // İlişkileri yükle
        $this->ogretmenDersler = OgretmenDers::with(['ogretmen', 'ders'])->get();
        $this->grupDersler = GrupDers::with(['ogrenciGrubu', 'ders'])->get();
        $this->dersMekanGereksinimleri = DersMekanGereksinimi::all();

        // Zaman dilimlerini ID'ye göre map'le (O(1) erişim için)
        foreach ($this->zamanDilimleri as $zd) {
            $this->zamanDilimleriById[$zd->id] = $zd;
        }

        // Müsaitlik ve Kısıtlamaları Map'e çevir (O(1) erişim için)
        // SADECE musaitlik_tipi = 'musait' olanları map'le
        $musaitlikler = OgretmenMusaitlik::where('musaitlik_tipi', 'musait')->get();
        foreach ($musaitlikler as $m) {
            $this->ogretmenMusaitlikMap[$m->ogretmen_id][$m->zaman_dilimi_id] = true;
        }

        // Grup kısıtlamaları: musait_mi = 0 olanlar kısıtlı (ders atanamaz)
        // musait_mi = 1 olanlar müsait (ders atanabilir)
        $kisitlamalar = GrupKisitlama::where('musait_mi', 0)->get();
        foreach ($kisitlamalar as $k) {
            $this->grupKisitlamaMap[$k->ogrenci_grup_id][$k->zaman_dilimi_id] = true;
        }

        // Ders-Mekan gereksinimlerini map'le
        foreach ($this->dersMekanGereksinimleri as $dmg) {
            $this->dersMekanMap[$dmg->ders_id] = [
                'mekan_tipi' => $dmg->mekan_tipi,
                'gereksinim_tipi' => $dmg->gereksinim_tipi,
            ];
        }

        // Mekanları tipe göre map'le
        foreach ($this->mekanlar as $mekan) {
            $this->mekanTipiMap[$mekan->id] = [
                'tip' => $mekan->mekan_tipi,
                'kapasite' => $mekan->kapasite,
            ];
        }

        // Grup kapasitelerini map'le
        foreach ($this->ogrenciGruplari as $grup) {
            $this->grupKapasiteMap[$grup->id] = $grup->ogrenci_sayisi ?? 30;
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
        // PHP timeout'u artır (15 dakika)
        set_time_limit(900);

        // İlk popülasyonu oluştur
        $population = $this->createInitialPopulation();

        $bestIndividual = null;
        $bestFitness = -PHP_INT_MAX;

        for ($generation = 0; $generation < $this->generations; $generation++) {
            // Her bireyin fitness'ını hesapla
            $fitnessScores = array_map(fn ($individual) => $this->calculateFitness($individual), $population);

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
            $individual = $this->createRandomIndividual();
            // Güvenlik için repair et (eksik blok olmaması için)
            $individual = $this->repairIndividual($individual);
            $population[] = $individual;
        }

        return $population;
    }

    /**
     * Rastgele bir birey (ders programı) oluştur
     * İYİLEŞTİRİLMİŞ: Öğretmen müsaitliğini dikkate alır
     */
    protected function createRandomIndividual(): array
    {
        $individual = [];

        foreach ($this->dersAtamalari as $atama) {
            $blockSize = $atama['block_size'];

            // Bu ders için uygun öğretmenleri bul
            $uygunOgretmenler = $this->ogretmenDersler
                ->where('ders_id', $atama['ders_id'])
                ->pluck('ogretmen_id')
                ->toArray();

            if (empty($uygunOgretmenler)) {
                continue;
            }

            // Öğretmen seç ve müsait olduğu slotları bul
            shuffle($uygunOgretmenler);
            $ogretmenId = null;
            $selectedSlots = null;

            foreach ($uygunOgretmenler as $potansiyelOgretmen) {
                // Bu öğretmenin müsait olduğu ardışık slotları bul
                $validBlocks = $this->findConsecutiveSlotsForTeacher($blockSize, $potansiyelOgretmen, $atama['grup_id']);

                if (!empty($validBlocks)) {
                    $ogretmenId = $potansiyelOgretmen;
                    $selectedSlots = $validBlocks[array_rand($validBlocks)];
                    break;
                }
            }

            // Hiçbir öğretmen için uygun slot bulunamadıysa, rastgele seç
            if ($ogretmenId === null) {
                $ogretmenId = $uygunOgretmenler[array_rand($uygunOgretmenler)];
                $validBlocks = $this->findConsecutiveSlots($blockSize);

                if (!empty($validBlocks)) {
                    $selectedSlots = $validBlocks[array_rand($validBlocks)];
                } else {
                    $selectedSlots = [];
                    for ($i = 0; $i < $blockSize; $i++) {
                        $selectedSlots[] = $this->zamanDilimleri->random();
                    }
                }
            }

            // Uygun mekan seç (tip ve kapasite dikkate alınarak)
            $mekan = $this->selectSuitableMekan($atama['ders_id'], $atama['grup_id']);

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
     * Belirli bir öğretmen ve grup için uygun ardışık slot kombinasyonları bul
     * Öğretmen müsaitliği ve grup kısıtlamalarını dikkate alır
     */
    protected function findConsecutiveSlotsForTeacher(int $blockSize, int $ogretmenId, int $grupId): array
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
                $isValid = true;

                for ($j = 0; $j < $blockSize; $j++) {
                    if ($i + $j >= $sortedSlots->count()) {
                        $isValid = false;
                        break;
                    }

                    $currentSlot = $sortedSlots[$i + $j];

                    // Öğretmen bu saatte müsait mi?
                    if (!isset($this->ogretmenMusaitlikMap[$ogretmenId][$currentSlot->id])) {
                        $isValid = false;
                        break;
                    }

                    // Grup bu saatte kısıtlı mı?
                    if (isset($this->grupKisitlamaMap[$grupId][$currentSlot->id])) {
                        $isValid = false;
                        break;
                    }

                    $block[] = $currentSlot;

                    // İlk slot değilse, bir önceki ile arka arkaya mı kontrol et
                    if ($j > 0) {
                        $prevSlot = $sortedSlots[$i + $j - 1];

                        $prevBitis = strtotime($prevSlot->bitis_saati);
                        $currBaslangic = strtotime($currentSlot->baslangic_saati);

                        if (abs($currBaslangic - $prevBitis) > 900) {
                            $isValid = false;
                            break;
                        }
                    }
                }

                if ($isValid && count($block) === $blockSize) {
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
     *
     * CEZA HİYERARŞİSİ (en önemliden en az önemliye):
     * 1. Çakışmalar (öğretmen/grup/mekan) - 1.000.000 puan
     * 2. Öğretmen müsaitliği - 1.000.000 puan
     * 3. Grup kısıtlamaları - 1.000.000 puan
     * 4. Ders saat eksikliği - 1.000.000 puan (YENİ)
     * 5. Blok bütünlüğü (arka arkaya olma) - 500.000 puan
     * 6. Aynı gün farklı blok olmaması - 500.000 puan
     * 7. Mekan tipi uygunluğu - 200.000 puan
     * 8. Mekan kapasitesi - 100.000 puan
     */
    protected function calculateFitness(array $individual): float
    {
        $score = 0;

        // ==================== SERT KISITLAR ====================
        // Bu kurallar KESİNLİKLE ihlal edilmemeli

        // 1. Aynı zaman diliminde aynı öğretmen farklı yerlerde olamaz
        $score -= $this->checkOgretmenCakismasi($individual) * 1000000;

        // 2. Aynı zaman diliminde aynı grup farklı derslerde olamaz
        $score -= $this->checkGrupCakismasi($individual) * 1000000;

        // 3. Aynı zaman diliminde aynı mekan birden fazla derse ayrılamaz
        $score -= $this->checkMekanCakismasi($individual) * 1000000;

        // 4. Öğretmen müsaitliği kontrolü (KESİNLİKLE UYULMALI)
        $score -= $this->checkOgretmenMusaitlik($individual) * 1000000;

        // 5. Grup kısıtlamaları kontrolü (KESİNLİKLE UYULMALI)
        $score -= $this->checkGrupKisitlama($individual) * 1000000;

        // 6. Ders saat eksikliği kontrolü (KESİNLİKLE UYULMALI)
        $score -= $this->checkDersSaatEksikligi($individual) * 1000000;

        // 7. Blokların arka arkaya olması kontrolü
        $score -= $this->checkBlockConsecutiveness($individual) * 500000;

        // 8. Aynı dersin farklı blokları aynı gün OLMAMALI
        $score -= $this->checkSameDayCourseDistribution($individual) * 500000;

        // 9. Mekan tipi uygunluğu kontrolü (lab dersi lab'da olmalı)
        $score -= $this->checkMekanTipiUygunlugu($individual) * 200000;

        // 10. Mekan kapasitesi kontrolü
        $score -= $this->checkMekanKapasitesi($individual) * 100000;

        // ==================== YUMUŞAK KISITLAR ====================
        // Bu kurallar tercih edilir ama zorunlu değil

        // 11. Aynı günde derslerin toplu olması tercih edilir
        $score += $this->calculateCompactnessScore($individual) * 100;

        // 12. Öğretmenlerin derslerinin de toplu olması tercih edilir
        $score += $this->calculateOgretmenCompactnessScore($individual) * 80;

        // 13. Aynı dersin saatlerinin haftaya dengeli dağılması
        $score += $this->calculateDersDistributionScore($individual) * 50;

        // 14. Mekan tipi tercihi (zorunlu olmayan ama tercih edilen)
        $score += $this->calculateMekanTercihi($individual) * 30;

        return $score;
    }

    /**
     * Ders saat eksikliği kontrolü (SERT KISIT)
     * Her dersin haftalık saati tam olarak atanmalı
     */
    protected function checkDersSaatEksikligi(array $individual): int
    {
        $violations = 0;

        // Grup+Ders bazında slot sayısını hesapla
        $grupDersSaatleri = [];
        foreach ($individual as $slot) {
            $key = $slot['grup_id'] . '-' . $slot['ders_id'];
            if (!isset($grupDersSaatleri[$key])) {
                $grupDersSaatleri[$key] = 0;
            }
            $grupDersSaatleri[$key]++;
        }

        // Her grup-ders için beklenen saat ile karşılaştır
        foreach ($this->grupDersler as $gd) {
            $key = $gd->ogrenci_grup_id . '-' . $gd->ders_id;
            $ders = $this->dersler->find($gd->ders_id);

            if (!$ders) continue;

            $beklenenSaat = $ders->haftalik_saat;
            $mevcutSaat = $grupDersSaatleri[$key] ?? 0;

            // Eksik veya fazla saat varsa ceza
            if ($mevcutSaat !== $beklenenSaat) {
                $violations += abs($beklenenSaat - $mevcutSaat);
            }
        }

        return $violations;
    }

    /**
     * Öğretmen çakışması kontrolü
     */
    protected function checkOgretmenCakismasi(array $individual): int
    {
        $violations = 0;
        $zamanOgretmen = [];

        foreach ($individual as $slot) {
            $key = $slot['zaman_dilim_id'].'-'.$slot['ogretmen_id'];
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
            $key = $slot['zaman_dilim_id'].'-'.$slot['grup_id'];
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
            $key = $slot['zaman_dilim_id'].'-'.$slot['mekan_id'];
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
            if (! isset($this->ogretmenMusaitlikMap[$ogretmenId][$zamanId])) {
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
     * Derslerin topluluk skoru (boşlukları minimize et) - HIZLI VERSİYON
     */
    protected function calculateCompactnessScore(array $individual): float
    {
        $score = 0;

        // Grup ve güne göre slotları grupla
        $grupGunSlotlari = [];
        foreach ($individual as $slot) {
            $zamanDilim = $this->zamanDilimleriById[$slot['zaman_dilim_id']] ?? null;
            if (!$zamanDilim) continue;

            $key = $slot['grup_id'] . '-' . $zamanDilim->gun_sirasi;
            if (!isset($grupGunSlotlari[$key])) {
                $grupGunSlotlari[$key] = [];
            }
            $grupGunSlotlari[$key][] = $zamanDilim->baslangic_saati;
        }

        // Her grup-gün kombinasyonu için analiz
        foreach ($grupGunSlotlari as $key => $saatler) {
            if (count($saatler) <= 1) continue;

            sort($saatler);

            // Ardışık dersleri kontrol et
            for ($i = 0; $i < count($saatler) - 1; $i++) {
                $fark = strtotime($saatler[$i + 1]) - strtotime($saatler[$i]);
                if ($fark <= 3600) { // 1 saat veya daha az fark
                    $score += 3;
                } else {
                    $score -= 2;
                }
            }
        }

        return $score;
    }

    /**
     * Öğretmenlerin derslerinin topluluk skoru - HIZLI VERSİYON
     */
    protected function calculateOgretmenCompactnessScore(array $individual): float
    {
        $score = 0;

        // Öğretmen ve güne göre slotları grupla
        $ogretmenGunSlotlari = [];
        foreach ($individual as $slot) {
            $zamanDilim = $this->zamanDilimleriById[$slot['zaman_dilim_id']] ?? null;
            if (!$zamanDilim) continue;

            $key = $slot['ogretmen_id'] . '-' . $zamanDilim->gun_sirasi;
            if (!isset($ogretmenGunSlotlari[$key])) {
                $ogretmenGunSlotlari[$key] = [];
            }
            $ogretmenGunSlotlari[$key][] = $zamanDilim->baslangic_saati;
        }

        // Her öğretmen-gün kombinasyonu için analiz
        foreach ($ogretmenGunSlotlari as $key => $saatler) {
            if (count($saatler) <= 1) continue;

            sort($saatler);

            for ($i = 0; $i < count($saatler) - 1; $i++) {
                $fark = strtotime($saatler[$i + 1]) - strtotime($saatler[$i]);
                if ($fark <= 3600) {
                    $score += 2;
                } else {
                    $score -= 1;
                }
            }
        }

        return $score;
    }

    /**
     * Ders saatlerinin haftaya dengeli dağılım skoru - HIZLI VERSİYON
     */
    protected function calculateDersDistributionScore(array $individual): float
    {
        $score = 0;

        // Grup+Ders bazında günleri topla
        $grupDersGunler = [];
        foreach ($individual as $slot) {
            $zamanDilim = $this->zamanDilimleriById[$slot['zaman_dilim_id']] ?? null;
            if (!$zamanDilim) continue;

            $key = $slot['grup_id'] . '-' . $slot['ders_id'];
            if (!isset($grupDersGunler[$key])) {
                $grupDersGunler[$key] = [];
            }
            $grupDersGunler[$key][$zamanDilim->gun_sirasi] = true;
        }

        // Farklı günlere yayılmış dersler için bonus
        foreach ($grupDersGunler as $key => $gunler) {
            $gunSayisi = count($gunler);
            if ($gunSayisi > 1) {
                $score += $gunSayisi * 2;
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

        // Elit bireyleri koru (onları da repair et)
        $elites = $this->selectElites($population, $fitnessScores);
        foreach ($elites as $elite) {
            $newPopulation[] = $this->repairIndividual($elite);
        }

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

            // Repair: Eksik blokları tamamla
            $child1 = $this->repairIndividual($child1);
            $child2 = $this->repairIndividual($child2);

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
        usort($combined, fn ($a, $b) => $b[1] <=> $a[1]);

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
            // AMA mutlaka her bloğu ekle! (Blok kaybolmasını önle)
            if (rand(0, 1) === 0) {
                // Child1: önce parent1'den, yoksa parent2'den al
                if (isset($blocks1[$blockKey])) {
                    $child1 = array_merge($child1, $blocks1[$blockKey]);
                } elseif (isset($blocks2[$blockKey])) {
                    $child1 = array_merge($child1, $blocks2[$blockKey]);
                }

                // Child2: önce parent2'den, yoksa parent1'den al
                if (isset($blocks2[$blockKey])) {
                    $child2 = array_merge($child2, $blocks2[$blockKey]);
                } elseif (isset($blocks1[$blockKey])) {
                    $child2 = array_merge($child2, $blocks1[$blockKey]);
                }
            } else {
                // Child1: önce parent2'den, yoksa parent1'den al
                if (isset($blocks2[$blockKey])) {
                    $child1 = array_merge($child1, $blocks2[$blockKey]);
                } elseif (isset($blocks1[$blockKey])) {
                    $child1 = array_merge($child1, $blocks1[$blockKey]);
                }

                // Child2: önce parent1'den, yoksa parent2'den al
                if (isset($blocks1[$blockKey])) {
                    $child2 = array_merge($child2, $blocks1[$blockKey]);
                } elseif (isset($blocks2[$blockKey])) {
                    $child2 = array_merge($child2, $blocks2[$blockKey]);
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
            $blockKey = $slot['grup_id'].'-'.$slot['ders_id'].'-'.$slot['block_index'];

            if (! isset($blocks[$blockKey])) {
                $blocks[$blockKey] = [];
            }

            $blocks[$blockKey][] = $slot;
        }

        return $blocks;
    }

    /**
     * Eksik blokları ve eksik slotları tamamla (Repair Mekanizması)
     *
     * Genetik algoritma sırasında bazı bloklar veya slotlar kaybolabilir.
     * Bu fonksiyon eksik blokları ve eksik slotları tespit edip ekler.
     *
     * GÜÇLENDIRILMIŞ VERSİYON: Fazla slotları da temizler
     */
    protected function repairIndividual(array $individual): array
    {
        // Hangi bloklara ihtiyacımız var?
        $requiredBlocks = [];
        foreach ($this->dersAtamalari as $atama) {
            $blockKey = $atama['grup_id'].'-'.$atama['ders_id'].'-'.$atama['block_index'];
            $requiredBlocks[$blockKey] = $atama;
        }

        // Individual'daki mevcut blokları ve slot sayılarını bul
        $existingBlocks = [];
        foreach ($individual as $idx => $slot) {
            $blockKey = $slot['grup_id'].'-'.$slot['ders_id'].'-'.$slot['block_index'];
            if (! isset($existingBlocks[$blockKey])) {
                $existingBlocks[$blockKey] = [
                    'count' => 0,
                    'indices' => [],
                    'slots' => [],
                ];
            }
            $existingBlocks[$blockKey]['count']++;
            $existingBlocks[$blockKey]['indices'][] = $idx;
            $existingBlocks[$blockKey]['slots'][] = $slot;
        }

        // Önce fazla veya eksik slotları olan blokları temizle
        $indicesToRemove = [];
        foreach ($requiredBlocks as $blockKey => $atama) {
            $blockSize = $atama['block_size'];
            $currentCount = $existingBlocks[$blockKey]['count'] ?? 0;

            // Blok tamam değilse (eksik veya fazla) tüm bloğu sil
            if ($currentCount !== $blockSize && $currentCount > 0) {
                foreach ($existingBlocks[$blockKey]['indices'] as $idx) {
                    $indicesToRemove[$idx] = true;
                }
                $existingBlocks[$blockKey]['count'] = 0;
                $existingBlocks[$blockKey]['indices'] = [];
                $existingBlocks[$blockKey]['slots'] = [];
            }
        }

        // Silinecek indeksleri çıkar
        if (!empty($indicesToRemove)) {
            $individual = array_values(array_filter($individual, function($slot, $idx) use ($indicesToRemove) {
                return !isset($indicesToRemove[$idx]);
            }, ARRAY_FILTER_USE_BOTH));
        }

        // Şimdi eksik blokları oluştur
        foreach ($requiredBlocks as $blockKey => $atama) {
            $blockSize = $atama['block_size'];
            $currentCount = $existingBlocks[$blockKey]['count'] ?? 0;

            if ($currentCount === $blockSize) {
                // Blok tamam, devam et
                continue;
            }

            // Blok eksik veya yok, oluştur
            $validBlocks = $this->findConsecutiveSlots($blockSize);

            if (empty($validBlocks)) {
                // Uygun ardışık slot bulunamadı, rastgele ata (fitness düşük olacak)
                $selectedSlots = [];
                for ($i = 0; $i < $blockSize; $i++) {
                    $selectedSlots[] = $this->zamanDilimleri->random();
                }
            } else {
                $selectedSlots = $validBlocks[array_rand($validBlocks)];
            }

            // Uygun mekan seç (tip ve kapasite dikkate alınarak)
            $mekan = $this->selectSuitableMekan($atama['ders_id'], $atama['grup_id']);

            $uygunOgretmenler = $this->ogretmenDersler
                ->where('ders_id', $atama['ders_id'])
                ->pluck('ogretmen_id')
                ->toArray();

            if (! empty($uygunOgretmenler)) {
                $ogretmenId = $uygunOgretmenler[array_rand($uygunOgretmenler)];

                for ($i = 0; $i < $blockSize; $i++) {
                    $zamanDilim = $selectedSlots[$i];

                    $individual[] = [
                        'grup_id' => $atama['grup_id'],
                        'ders_id' => $atama['ders_id'],
                        'block_index' => $atama['block_index'],
                        'block_size' => $blockSize,
                        'slot_in_block' => $i,
                        'ogretmen_id' => $ogretmenId,
                        'zaman_dilim_id' => $zamanDilim->id,
                        'mekan_id' => $mekan->id,
                    ];
                }
            }
        }

        return $individual;
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
        $blockKey = $randomSlot['grup_id'].'-'.$randomSlot['ders_id'].'-'.$randomSlot['block_index'];
        $blockIndices = [];

        foreach ($individual as $idx => $slot) {
            $slotBlockKey = $slot['grup_id'].'-'.$slot['ders_id'].'-'.$slot['block_index'];
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

                if (! empty($validBlocks)) {
                    $newSlots = $validBlocks[array_rand($validBlocks)];

                    foreach ($blockIndices as $i => $blockIdx) {
                        if (isset($newSlots[$i])) {
                            $individual[$blockIdx]['zaman_dilim_id'] = $newSlots[$i]->id;
                        }
                    }
                }
                break;

            case 1: // Mekan değiştir (TÜM BLOK için aynı mekan - tip ve kapasite dikkate alınarak)
                $newMekan = $this->selectSuitableMekan($randomSlot['ders_id'], $randomSlot['grup_id']);
                foreach ($blockIndices as $blockIdx) {
                    $individual[$blockIdx]['mekan_id'] = $newMekan->id;
                }
                break;

            case 2: // Öğretmen değiştir (TÜM BLOK için aynı öğretmen)
                $uygunOgretmenler = $this->ogretmenDersler
                    ->where('ders_id', $randomSlot['ders_id'])
                    ->pluck('ogretmen_id')
                    ->toArray();

                if (! empty($uygunOgretmenler)) {
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
                    \Log::error("Slot kaydetme hatası (DersID: {$slot['ders_id']}, GrupID: {$slot['grup_id']}): ".$e->getMessage());
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
            $blockKey = $slot['grup_id'].'-'.$slot['ders_id'].'-'.$slot['block_index'];

            if (! isset($blocks[$blockKey])) {
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

            // Zaman dilimlerini saate göre sırala (map kullanarak hızlı erişim)
            usort($slots, function ($a, $b) {
                $zamanA = $this->zamanDilimleriById[$a['zaman_dilim_id']] ?? null;
                $zamanB = $this->zamanDilimleriById[$b['zaman_dilim_id']] ?? null;
                if (!$zamanA || !$zamanB) return 0;
                return strcmp($zamanA->baslangic_saati, $zamanB->baslangic_saati);
            });

            // Arka arkaya mı kontrol et
            for ($i = 1; $i < count($slots); $i++) {
                $prevZamanDilim = $this->zamanDilimleriById[$slots[$i - 1]['zaman_dilim_id']] ?? null;
                $currZamanDilim = $this->zamanDilimleriById[$slots[$i]['zaman_dilim_id']] ?? null;

                if (!$prevZamanDilim || !$currZamanDilim) {
                    $violations++;
                    continue;
                }

                // Aynı gün mü?
                if ($prevZamanDilim->haftanin_gunu !== $currZamanDilim->haftanin_gunu) {
                    $violations++;
                    continue;
                }

                // Saat olarak arka arkaya mı? (bitiş = başlangıç veya max 15dk fark)
                $prevBitis = strtotime($prevZamanDilim->bitis_saati);
                $currBaslangic = strtotime($currZamanDilim->baslangic_saati);

                if (abs($currBaslangic - $prevBitis) > 900) {
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
            $key = $slot['grup_id'].'-'.$slot['ders_id'];
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
                    $zamanDilim = $this->zamanDilimleriById[$slot['zaman_dilim_id']] ?? null;
                    if ($zamanDilim) {
                        $blockDays[$blockIndex] = $zamanDilim->haftanin_gunu;
                    }
                }
            }

            // Günlerin benzersiz olup olmadığını kontrol et
            $days = array_values($blockDays);
            $uniqueDays = array_unique($days);

            // Eğer gün sayısı, blok sayısından azsa -> çakışma var demektir
            if (count($days) !== count($uniqueDays)) {
                $violations += (count($days) - count($uniqueDays));
            }
        }

        return $violations;
    }

    /**
     * Mekan tipi uygunluğu kontrolü (SERT KISIT)
     * Lab dersi lab'da, normal ders sınıfta olmalı
     */
    protected function checkMekanTipiUygunlugu(array $individual): int
    {
        $violations = 0;

        foreach ($individual as $slot) {
            $dersId = $slot['ders_id'];
            $mekanId = $slot['mekan_id'];

            // Bu ders için mekan gereksinimi var mı?
            if (! isset($this->dersMekanMap[$dersId])) {
                continue; // Gereksinim yoksa sorun yok
            }

            $gereksinim = $this->dersMekanMap[$dersId];

            // Sadece 'zorunlu' gereksinimler sert kısıt
            if ($gereksinim['gereksinim_tipi'] !== 'zorunlu') {
                continue;
            }

            // Mekanın tipini kontrol et
            if (! isset($this->mekanTipiMap[$mekanId])) {
                $violations++;

                continue;
            }

            $mekanTipi = strtolower($this->mekanTipiMap[$mekanId]['tip']);
            $gerekliTip = strtolower($gereksinim['mekan_tipi']);

            // Tip uyuşmuyor mu? (case-insensitive karşılaştırma)
            if ($mekanTipi !== $gerekliTip) {
                $violations++;
            }
        }

        return $violations;
    }

    /**
     * Mekan kapasitesi kontrolü (SERT KISIT)
     * Grup öğrenci sayısı mekan kapasitesini aşmamalı
     */
    protected function checkMekanKapasitesi(array $individual): int
    {
        $violations = 0;

        foreach ($individual as $slot) {
            $grupId = $slot['grup_id'];
            $mekanId = $slot['mekan_id'];

            // Grup kapasitesini al
            $grupKapasite = $this->grupKapasiteMap[$grupId] ?? 30;

            // Mekan kapasitesini al
            if (! isset($this->mekanTipiMap[$mekanId])) {
                continue;
            }

            $mekanKapasite = $this->mekanTipiMap[$mekanId]['kapasite'];

            // Kapasite aşılıyor mu?
            if ($grupKapasite > $mekanKapasite) {
                $violations++;
            }
        }

        return $violations;
    }

    /**
     * Mekan tipi tercihi (YUMUŞAK KISIT)
     * Tercih edilen mekan tipine uygunluk için bonus puan
     */
    protected function calculateMekanTercihi(array $individual): float
    {
        $score = 0;

        foreach ($individual as $slot) {
            $dersId = $slot['ders_id'];
            $mekanId = $slot['mekan_id'];

            // Bu ders için mekan gereksinimi var mı?
            if (! isset($this->dersMekanMap[$dersId])) {
                continue;
            }

            $gereksinim = $this->dersMekanMap[$dersId];

            // Sadece 'tercih' veya 'olabilir' gereksinimler yumuşak kısıt
            if ($gereksinim['gereksinim_tipi'] !== 'tercih' && $gereksinim['gereksinim_tipi'] !== 'olabilir') {
                continue;
            }

            // Mekanın tipini kontrol et
            if (! isset($this->mekanTipiMap[$mekanId])) {
                continue;
            }

            $mekanTipi = strtolower($this->mekanTipiMap[$mekanId]['tip']);
            $tercihEdilenTip = strtolower($gereksinim['mekan_tipi']);

            // Tercih edilen tipe uyuyorsa bonus (case-insensitive)
            if ($mekanTipi === $tercihEdilenTip) {
                $score += 5;
            }
        }

        return $score;
    }

    /**
     * Ders ve grup için uygun mekan seç
     * Önce tip ve kapasite uygun olanları filtrele, sonra rastgele seç
     */
    protected function selectSuitableMekan(int $dersId, int $grupId): Mekan
    {
        $grupKapasite = $this->grupKapasiteMap[$grupId] ?? 30;

        // Ders için mekan gereksinimi var mı?
        $gerekliTip = null;
        $zorunluMu = false;

        if (isset($this->dersMekanMap[$dersId])) {
            $gerekliTip = strtolower($this->dersMekanMap[$dersId]['mekan_tipi']);
            $zorunluMu = $this->dersMekanMap[$dersId]['gereksinim_tipi'] === 'zorunlu';
        }

        // Uygun mekanları filtrele (case-insensitive karşılaştırma)
        $uygunMekanlar = $this->mekanlar->filter(function ($mekan) use ($grupKapasite, $gerekliTip, $zorunluMu) {
            // Kapasite kontrolü
            if ($mekan->kapasite < $grupKapasite) {
                return false;
            }

            // Tip kontrolü (sadece zorunlu ise, case-insensitive)
            if ($zorunluMu && $gerekliTip !== null && strtolower($mekan->mekan_tipi) !== $gerekliTip) {
                return false;
            }

            return true;
        });

        // Uygun mekan varsa onlardan seç
        if ($uygunMekanlar->isNotEmpty()) {
            // Tercih edilen tipe uyanları önceliklendir (case-insensitive)
            if ($gerekliTip !== null && ! $zorunluMu) {
                $tercihliMekanlar = $uygunMekanlar->filter(fn ($m) => strtolower($m->mekan_tipi) === $gerekliTip);
                if ($tercihliMekanlar->isNotEmpty()) {
                    return $tercihliMekanlar->random();
                }
            }

            return $uygunMekanlar->random();
        }

        // Hiç uygun mekan yoksa rastgele seç (fitness düşük olacak)
        return $this->mekanlar->random();
    }
}
