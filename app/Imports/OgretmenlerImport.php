<?php

namespace App\Imports;

use App\Models\Ogretmen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class OgretmenlerImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
        // E-posta varsa ona göre, yoksa isme göre kontrol et
        $query = Ogretmen::query();

        if (!empty($row['e_posta'])) {
            $ogretmen = Ogretmen::updateOrCreate(
                ['email' => $row['e_posta']],
                [
                    'isim' => $row['ad_soyad'],
                    'unvan' => $row['unvan'],
                ]
            );
        } else {
            $ogretmen = Ogretmen::updateOrCreate(
                ['isim' => $row['ad_soyad']],
                [
                    'unvan' => $row['unvan'],
                    'email' => $row['e_posta'] ?? null,
                ]
            );
        }

        if ($ogretmen->wasRecentlyCreated) {
            $this->successCount++;
        } else {
            $this->updateCount++;
        }

        return $ogretmen;
    }

    /**
     * Validasyon kuralları
     */
    public function rules(): array
    {
        return [
            'ad_soyad' => 'required|string|max:255',
            'unvan' => 'required|string|max:100',
            'e_posta' => 'nullable|email|max:255',
        ];
    }

    /**
     * Özel validasyon mesajları
     */
    public function customValidationMessages()
    {
        return [
            'ad_soyad.required' => 'Ad Soyad zorunludur',
            'ad_soyad.max' => 'Ad Soyad en fazla 255 karakter olabilir',
            'unvan.required' => 'Unvan zorunludur',
            'unvan.max' => 'Unvan en fazla 100 karakter olabilir',
            'e_posta.email' => 'Geçerli bir e-posta adresi giriniz',
            'e_posta.max' => 'E-posta en fazla 255 karakter olabilir',
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
