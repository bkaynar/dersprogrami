<?php

namespace App\Imports;

use App\Models\Ders;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class DerslerImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable;

    protected $errors = [];
    protected $failures = [];
    protected $successCount = 0;
    protected $updateCount = 0;

    /**
     * Excel satırını model'e dönüştür
     */
    public function model(array $row)
    {
        // Ders koduna göre kontrol et, varsa güncelle yoksa oluştur
        $ders = Ders::updateOrCreate(
            ['ders_kodu' => $row['ders_kodu']],
            [
                'isim' => $row['ders_adi'],
                'aciklama' => $row['aciklama'] ?? null,
                'haftalik_saat' => (int) $row['haftalik_saat'],
                'ders_tipi' => $row['ders_tipi'],
            ]
        );

        if ($ders->wasRecentlyCreated) {
            $this->successCount++;
        } else {
            $this->updateCount++;
        }

        return $ders;
    }

    /**
     * Validasyon kuralları
     */
    public function rules(): array
    {
        return [
            'ders_kodu' => 'required|string|max:20',
            'ders_adi' => 'required|string|max:255',
            'aciklama' => 'nullable|string',
            'haftalik_saat' => 'required|integer|min:1|max:20',
            'ders_tipi' => 'required|in:Teorik,Uygulama,Laboratuvar',
        ];
    }

    /**
     * Özel validasyon mesajları
     */
    public function customValidationMessages()
    {
        return [
            'ders_kodu.required' => 'Ders kodu zorunludur',
            'ders_kodu.max' => 'Ders kodu en fazla 20 karakter olabilir',
            'ders_adi.required' => 'Ders adı zorunludur',
            'ders_adi.max' => 'Ders adı en fazla 255 karakter olabilir',
            'haftalik_saat.required' => 'Haftalık saat zorunludur',
            'haftalik_saat.integer' => 'Haftalık saat sayı olmalıdır',
            'haftalik_saat.min' => 'Haftalık saat en az 1 olmalıdır',
            'haftalik_saat.max' => 'Haftalık saat en fazla 20 olabilir',
            'ders_tipi.required' => 'Ders tipi zorunludur',
            'ders_tipi.in' => 'Ders tipi Teorik, Uygulama veya Laboratuvar olmalıdır',
        ];
    }

    /**
     * Hata durumunda
     */
    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    /**
     * Validasyon hatası durumunda
     */
    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    /**
     * İstatistikleri al
     */
    public function getStats(): array
    {
        return [
            'success' => $this->successCount,
            'updated' => $this->updateCount,
            'errors' => count($this->errors),
            'failures' => count($this->failures),
            'total' => $this->successCount + $this->updateCount,
        ];
    }

    /**
     * Hata detaylarını al
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Validasyon hatalarını al
     */
    public function getFailures(): array
    {
        return array_map(function ($failure) {
            return [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ];
        }, $this->failures);
    }
}
