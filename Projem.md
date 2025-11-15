# Akıllı Ders Programı Otomasyon Sistemi

Bu proje, bir üniversite bölümü için haftalık ders programlarını, tanımlanan kısıtlamalara göre **otomatik ve optimum** bir şekilde oluşturan yapay zeka destekli bir web uygulamasıdır.

Projenin temel amacı, manuel olarak yapılması günler süren, çakışmalara ve verimsizliklere açık olan ders programı hazırlama sürecini saniyelere indirmek ve tüm paydaşlar (öğretmenler, öğrenciler, idare) için en verimli sonucu üretmektir.

## 1. Problemin Tanımı

Bir eğitim kurumunda ders programı hazırlamak, çok katmanlı bir optimizasyon problemidir. Bu problemde:
* **Öğretmenlerin** aynı anda tek bir yerde olabilmesi.
* **Sınıfların/Şubelerin** aynı anda tek bir ders alabilmesi.
* **Dersliklerin/Laboratuvarların** aynı anda tek bir ders için kullanılabilmesi.
* Dersin tipine göre (örn: "Lab Dersi") doğru mekanda (örn: "Laboratuvar") işlenmesi zorunluluğu.
* Öğretmenlerin müsaitlik durumları (örn: "Ali Hoca sadece Salı/Çarşamba gelir").
* Sınıfların kısıtlamaları (örn: "1. sınıfların Cuma günü dersi olmasın").

...gibi onlarca kuralın aynı anda ihlal edilmeden çözülmesi gerekir.

## 2. Sunulan Çözüm: Yapay Zeka Destekli Optimizasyon

Bu proje, bu problemi çözmek için basit bir çakışma kontrolü yazılımından öteye geçer. Sistemin çekirdeğinde, en optimum çözümü bulmak için **Genetik Algoritmalar (GA)** kullanan bir yapay zeka motoru bulunur.

Bu motor, sadece kurallara uyan bir program bulmakla kalmaz, aynı zamanda:
* Öğretmen ve öğrencilerin ders aralarındaki boşlukları minimize eder.
* Dersleri mümkün olduğunca blok halinde toplar.
* Tanımlanan "yumuşak kısıtlamalara" (tercihlere) en çok uyan programı üretir.

## 3. Ana Özellikler

* **Akıllı Optimizasyon Motoru:** Genetik Algoritma kullanarak milyonlarca olasılık arasından en verimli ders programını bulur.
* **Dinamik Kısıt Yönetimi:**
    * **Öğretmen Yönetimi:** Müsaitlik durumlarını (gün/saat bazlı) tanımlayabilme.
    * **Derslik/Lab Yönetimi:** Kapasite ve mekan tipi (derslik, lab vb.) tanımlayabilme.
    * **Ders Yönetimi:** Hangi dersin hangi tip mekanda işlenmesi gerektiğini (`zorunlu` veya `olabilir`) belirtebilme.
    * **Sınıf Yönetimi:** Şubeleri ve ana sınıfları hiyerarşik olarak yönetebilme, sınıflara özel kısıtlama (örn: tatil günü) ekleyebilme.
* **Çoklu İlişki Yönetimi:** Hangi öğretmenin hangi dersleri verebileceğini ve hangi sınıfın hangi dersleri alacağını eşleştirebilme.
* **Görsel Program Çıktısı:** Oluşturulan programı haftalık bir zaman çizelgesi olarak görüntüleyebilme.

## 4. Teknoloji Yığını

* **Backend:** PHP 8.4
* **Framework:** Laravel 11 (veya mevcut sürümünüz)
* **Veritabanı:** MySQL (veya PostgreSQL)
* **Paket Yönetimi:** Composer
* **Çekirdek Motor:** Harici bir PHP Genetik Algoritma kütüphanesi.
* **Yönetim Paneli:** Laravel'in kendi Blade/Controller yapısı veya hız kazanmak için **FilamentPHP** / **Laravel Nova** gibi bir admin paneli paketi.

## 5. Çekirdek Motor: Genetik Algoritma Nasıl Çalışır?

Projenin "yapay zeka" kısmı burasıdır ve bir API hizmeti (ChatGPT gibi) **kullanmaz**. Algoritma tamamen sunucuda, PHP koduyla çalışır:

1.  **Popülasyon Oluşturma:** Sistem, kurallara uymayan yüzlerce rastgele ders programı "bireyi" oluşturur.
2.  **Uygunluk Fonksiyonu (Fitness Function):** Projenin beyni burasıdır. Yazdığımız bu özel fonksiyon, her bir programı eline alır ve veritabanındaki kurallara göre puanlar.
    * **Sert Kısıtlar (Ceza):** Bir çakışma (hoca, sınıf, mekan) varsa çok büyük ceza puanı verir (örn: -1000).
    * **Yumuşak Kısıtlar (Ceza/Ödül):** Hocanın dersleri arasında 3 saat boşluk varsa küçük bir ceza (örn: -10), dersleri blok haldeyse bir ödül (örn: +5) verir.
3.  **Seçilim (Selection):** En yüksek puana sahip (en az ceza alan) programlar "hayatta kalır".
4.  **Çaprazlama (Crossover):** İki iyi programın başarılı kısımları (örn: birinin Pazartesi programı, diğerinin Salı programı) birleştirilerek yeni "çocuk" programlar oluşturulur.
5.  **Mutasyon (Mutation):** Çözümün yerel bir optimuma takılmaması için programda rastgele küçük değişiklikler yapılır (örn: bir dersin saati aniden değiştirilir).
6.  **Sonuç:** Bu döngü binlerce kez (saniyeler içinde) tekrarlanır ve "uygunluk puanı" en yüksek olan, yani tüm kurallara uyan ve en verimli olan program sonuç olarak `olusturulan_program` tablosuna kaydedilir.

## 6. Veritabanı Şeması (Ana Tablolar)

Veritabanı, optimizasyon motorunun ihtiyaç duyacağı tüm verileri ve kuralları saklamak üzere tasarlanmıştır:

* **Ana Varlıklar:** `ogretmenler`, `dersler`, `mekanlar`, `ogrenci_gruplari`, `zaman_dilimleri`.
* **Kural/Kısıt Tabloları:** `ogretmen_musaitlik`, `grup_kisitlamalari`, `ders_mekan_gereksinimleri`.
* **İlişki (Pivot) Tabloları:** `ogretmen_dersleri` (Hangi hoca hangi dersi verebilir), `grup_dersleri` (Hangi grup hangi dersi alır).
* **Çıktı Tablosu:** `olusturulan_program` (Algoritmanın nihai sonucu).

## 7. Kurulum ve Çalıştırma

1.  Projeyi klonlayın:
    ```bash
    git clone [repository-url]
    cd [proje-klasoru]
    ```
2.  Composer paketlerini yükleyin:
    ```bash
    composer install
    ```
3.  `.env` dosyasını oluşturun ve veritabanı bilgilerinizi girin:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  Veritabanı migration'larını çalıştırın:
    ```bash
    php artisan migrate
    ```
5.  (Opsiyonel) Test verileri için Seeder'ları çalıştırın:
    ```bash
    php artisan db:seed
    ```
6.  Sunucuyu başlatın:
    ```bash
    php artisan serve
    ```

## 8. Proje Yol Haritası (Mevcut Durum ve Sonraki Adımlar)

-   [x] 1. Proje Fikir Analizi ve Teknoloji Seçimi (Laravel + GA).
-   [x] 2. Detaylı Veritabanı Şeması Tasarımı (DBML).
-   [x] 3. Laravel Migration Dosyalarının Oluşturulması.
-   [x] 4. Eloquent Modellerinin Oluşturulması.
-   [x] 5. Model İlişkilerinin (Relationships) Tanımlanması.
-   [x] 6. Yönetim Paneli (CRUD Arayüzü) Kurulumu (Vue + Inertia.js ile tamamlandı).
-   [x] 7. Temel Veri Girişi (Seeder veya manuel) ve Test Verilerinin Oluşturulması.
-   [x] 8. Genetik Algoritma Kütüphanesi Seçimi ve Entegrasyonu (ryanhs/simple-genetic-algorithm).
-   [x] **9. Uygunluk Fonksiyonu'nun (Fitness Function) Kodlanması.** (Sert ve yumuşak kısıtlar implement edildi)
-   [x] 10. Algoritmayı Tetikleyecek Bir Servis Sınıfı ve Controller Yazılması (TimetableGeneticAlgorithm + ProgramOlusturController).
-   [x] **11. Sonuç Programının Arayüzde (Vue) Gösterilmesi.** (Şu anki adım - Tamamlandı!)
-   [ ] 12. Fitness Fonksiyonu İyileştirmeleri (Compactness score, ders blokları optimizasyonu).
-   [ ] 13. Performans İyileştirmeleri (Background job, cache, ilerleme göstergesi).
-   [ ] 14. (Gelecek Özellikler) Dinamik yeniden zamanlama, "What-If" senaryoları, manuel düzenleme.

