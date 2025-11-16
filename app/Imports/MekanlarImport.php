<?php

namespace App\Imports;

use App\Models\Mekan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class MekanlarImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
        // Mekan adına göre kontrol et, varsa güncelle yoksa oluştur
        $mekan = Mekan::updateOrCreate(
            ['isim' => $row['mekan_adi']],
            [
                'kapasite' => (int) $row['kapasite'],
                'mekan_tipi' => $row['mekan_tipi'],
            ]
        );

        if ($mekan->wasRecentlyCreated) {
            $this->successCount++;
        } else {
            $this->updateCount++;
        }

        return $mekan;
    }

    /**
     * Validasyon kuralları
     */
    public function rules(): array
    {
        return [
            'mekan_adi' => 'required|string|max:255',
            'kapasite' => 'required|integer|min:1|max:1000',
            'mekan_tipi' => 'required|in:Derslik,Amfi,Laboratuvar,Salon,Atölye',
        ];
    }

    /**
     * Özel validasyon mesajları
     */
    public function customValidationMessages()
    {
        return [
            'mekan_adi.required' => 'Mekan adı zorunludur',
            'mekan_adi.max' => 'Mekan adı en fazla 255 karakter olabilir',
            'kapasite.required' => 'Kapasite zorunludur',
            'kapasite.integer' => 'Kapasite sayı olmalıdır',
            'kapasite.min' => 'Kapasite en az 1 olmalıdır',
            'kapasite.max' => 'Kapasite en fazla 1000 olabilir',
            'mekan_tipi.required' => 'Mekan tipi zorunludur',
            'mekan_tipi.in' => 'Mekan tipi Derslik, Amfi, Laboratuvar, Salon veya Atölye olmalıdır',
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
