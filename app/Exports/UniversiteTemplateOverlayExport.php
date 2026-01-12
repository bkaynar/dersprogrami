<?php

namespace App\Exports;

use App\Models\OgrenciGrubu;
use App\Models\OlusturulanProgram;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\File;

class UniversiteTemplateOverlayExport implements FromArray, WithEvents
{
    protected $subeType;
    protected $akademikYil;
    protected $donem;

    public function __construct($subeType = 'A', $akademikYil = '2024-2025', $donem = 'GÜZ')
    {
        $this->subeType = $subeType;
        $this->akademikYil = $akademikYil;
        $this->donem = $donem;
    }

    public function array(): array
    {
        // Boş array döndür, template'i AfterSheet event'inde yükleyeceğiz
        return [[]];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->loadTemplateAndFillData($event->sheet);
            },
        ];
    }

    private function loadTemplateAndFillData($sheet)
    {
        // Template dosyasının yolu
        $templatePath = storage_path('app/templates/universite_template.xlsx');

        if (!file_exists($templatePath)) {
            throw new \Exception('Template dosyası bulunamadı: ' . $templatePath);
        }

        // Template'i yükle
        $templateSpreadsheet = IOFactory::load($templatePath);
        $templateWorksheet = $templateSpreadsheet->getActiveSheet();

        // Mevcut sheet'i al
        $currentSheet = $sheet->getDelegate();

        // Template'in tüm içeriğini kopyala
        $this->copyTemplateContent($templateWorksheet, $currentSheet);

        // Başlığı güncelle
        $this->updateHeader($currentSheet);

        // Template üzerine veri yaz
        $this->fillTemplate($currentSheet);
    }

    private function copyTemplateContent($templateSheet, $targetSheet)
    {
        // Template'in boyutlarını al
        $highestRow = $templateSheet->getHighestRow();
        $highestColumn = $templateSheet->getHighestColumn();

        // Tüm hücreleri kopyala
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $templateCell = $templateSheet->getCell($col . $row);

                // Değeri kopyala
                $targetSheet->setCellValue($col . $row, $templateCell->getValue());

                // Stili kopyala
                $targetSheet->getStyle($col . $row)->applyFromArray(
                    $templateCell->getStyle()->exportArray()
                );
            }
        }

        // Satır yüksekliklerini kopyala
        for ($row = 1; $row <= $highestRow; $row++) {
            $height = $templateSheet->getRowDimension($row)->getRowHeight();
            if ($height > 0) {
                $targetSheet->getRowDimension($row)->setRowHeight($height);
            }
        }

        // Sütun genişliklerini kopyala
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $width = $templateSheet->getColumnDimension($col)->getWidth();
            if ($width > 0) {
                $targetSheet->getColumnDimension($col)->setWidth($width);
            }
        }

        // Merged cell'leri kopyala
        foreach ($templateSheet->getMergeCells() as $mergeRange) {
            try {
                $targetSheet->mergeCells($mergeRange);
            } catch (\Exception $e) {
                // Merge işlemi başarısız olursa devam et
                continue;
            }
        }
    }

    private function updateHeader($worksheet)
    {
        // Başlığı güncelle - template'te A1 hücresinde başlık var
        $baslik = "KIRŞEHİR AHİ EVRAN ÜNİVERSİTESİ MÜHENDİSLİK-MİMARLIK FAKÜLTESİ\nBİLGİSAYAR MÜHENDİSLİĞİ BÖLÜMÜ (TÜRKÇE) {$this->akademikYil} EĞİTİM-ÖĞRETİM YILI {$this->donem} DÖNEMİ HAFTALIK DERS PROGRAMI ({$this->subeType} ŞUBESİ)";
        $worksheet->setCellValue('A1', $baslik);

        // Başlık stilini koru
        $worksheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $worksheet->getStyle('A1')->getAlignment()->setWrapText(true);
    }

    private function fillTemplate($worksheet)
    {
        // Program verilerini al
        $programData = $this->getProgramData();

        // Şablondaki satır mapping'i (analiz sonuçlarına göre)
        $gunRowMapping = [
            'pazartesi' => 6,   // PAZARTESİ satır 6'da başlıyor
            'sali' => 15,       // SALI satır 15'te başlıyor
            'carsamba' => 24,   // ÇARŞAMBA satır 24'te başlıyor
            'persembe' => 33,   // PERŞEMBE satır 33'te başlıyor
            'cuma' => 42        // CUMA satır 42'de başlıyor
        ];

        // Saat mapping'i (her günde aynı sıra)
        $saatRowOffset = [
            '08:15 – 09:00' => 0,
            '09:15 – 10:00' => 1,
            '10:15 – 11:00' => 2,
            '11:15 – 12:00' => 3,
            '12:00 – 13:00' => 4,
            '13:00 – 13:45' => 5,
            '14:00 – 14:45' => 6,
            '15:00 – 15:45' => 7,
            '16:00 – 16:45' => 8
        ];

        // Sınıf sütun mapping'i
        $sinifColumns = [
            1 => ['C', 'D', 'E', 'F'], // 1. sınıf: DERS KODU, DERS ADI, DERSLİK, ÖĞRETİM ELEMANI
            2 => ['G', 'H', 'I', 'J'], // 2. sınıf
            3 => ['K', 'L', 'M', 'N'], // 3. sınıf
            4 => ['O', 'P', 'Q', 'R']  // 4. sınıf
        ];

        foreach ($programData as $data) {
            $gun = $data['gun'];
            $saat = $data['saat'];
            $sinif = $data['sinif'];

            if (isset($gunRowMapping[$gun]) && isset($saatRowOffset[$saat]) && isset($sinifColumns[$sinif])) {
                $baseRow = $gunRowMapping[$gun];
                $row = $baseRow + $saatRowOffset[$saat];

                $cols = $sinifColumns[$sinif];

                // Veriyi hücrelere yaz - orijinal template'in üzerine
                $worksheet->setCellValue($cols[0] . $row, $data['ders_kodu']);
                $worksheet->setCellValue($cols[1] . $row, $data['ders_adi']);
                $worksheet->setCellValue($cols[2] . $row, $data['derslik']);
                $worksheet->setCellValue($cols[3] . $row, $data['ogretim_elemani']);
            }
        }
    }

    private function getProgramData()
    {
        $data = [];

        // Template'teki saat formatları ile veritabanındaki saatleri eşleştir
        $saatMapping = [
            '08:00' => '08:15 – 09:00',
            '09:00' => '09:15 – 10:00',
            '10:00' => '10:15 – 11:00',
            '11:00' => '11:15 – 12:00',
            '12:00' => '12:00 – 13:00',
            '13:00' => '13:00 – 13:45',
            '14:00' => '14:00 – 14:45',
            '15:00' => '15:00 – 15:45',
            '16:00' => '16:00 – 16:45'
        ];

        $gunSirasi = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma'];

        // Her sınıf için ders bilgileri
        for ($sinif = 1; $sinif <= 4; $sinif++) {
            $grupAdi = "{$sinif}-{$this->subeType}";
            $grup = OgrenciGrubu::where('isim', $grupAdi)->first();

            if ($grup) {
                // Bu grubun tüm programlarını al
                $programlar = OlusturulanProgram::where('ogrenci_grup_id', $grup->id)
                    ->with(['ders', 'ogretmen', 'mekan', 'zamanDilim'])
                    ->get();

                foreach ($programlar as $program) {
                    $zamanDilim = $program->zamanDilim;
                    $gun = $zamanDilim->haftanin_gunu;
                    $baslangicSaat = substr($zamanDilim->baslangic_saati, 0, 5); // 08:00:00 -> 08:00

                    // Saat mapping'ini kontrol et
                    if (in_array($gun, $gunSirasi) && isset($saatMapping[$baslangicSaat])) {
                        $templateSaat = $saatMapping[$baslangicSaat];

                        $data[] = [
                            'gun' => $gun,
                            'saat' => $templateSaat,
                            'sinif' => $sinif,
                            'ders_kodu' => $program->ders->ders_kodu ?? '',
                            'ders_adi' => $program->ders->isim ?? '',
                            'derslik' => $program->mekan->isim ?? '',
                            'ogretim_elemani' => $this->formatOgretmenAdi($program->ogretmen->isim ?? '')
                        ];
                    }
                }
            }
        }

        return $data;
    }

    private function formatOgretmenAdi($ad): string
    {
        if (empty($ad)) {
            return '';
        }

        // Unvanları temizle
        $ad = preg_replace('/^(Prof\.|Dr\.|Doç\.|Öğr\.|Üyesi|Gör\.)\s*/i', '', $ad);
        $ad = trim($ad);

        // Boşluklara göre ayır
        $parcalar = explode(' ', $ad);

        if (count($parcalar) == 1) {
            // Tek kelime ise olduğu gibi döndür
            return strtoupper($parcalar[0]);
        }

        if (count($parcalar) == 2) {
            // İki kelime: "Fatih KOÇ" -> "F.KOÇ"
            $ilkHarf = strtoupper(substr($parcalar[0], 0, 1));
            $soyad = strtoupper($parcalar[1]);
            return $ilkHarf . '.' . $soyad;
        }

        if (count($parcalar) >= 3) {
            // Üç veya daha fazla kelime: "Gülsüm Akkuzu Kaya" -> "G.A.KAYA"
            $kisaltma = '';

            // Son kelime hariç tüm kelimelerin ilk harfini al
            for ($i = 0; $i < count($parcalar) - 1; $i++) {
                $kisaltma .= strtoupper(substr($parcalar[$i], 0, 1)) . '.';
            }

            // Son kelimeyi (soyad) ekle
            $kisaltma .= strtoupper($parcalar[count($parcalar) - 1]);

            return $kisaltma;
        }

        return strtoupper($ad);
    }
}
