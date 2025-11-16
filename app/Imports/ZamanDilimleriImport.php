<?php

namespace App\Imports;

use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class ZamanDilimleriImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable;

    protected $errors = [];
    protected $failures = [];
    protected $successCount = 0;
    protected $updateCount = 0;

    public function model(array $row)
    {
        // Benzersiz anahtar: gün + başlama saati
        $zaman = ZamanDilim::updateOrCreate(
            ['haftanin_gunu' => $row['haftanin_gunu'], 'baslangic_saati' => $row['baslangic_saati']],
            [
                'bitis_saati' => $row['bitis_saati'],
            ]
        );

        if ($zaman->wasRecentlyCreated) {
            $this->successCount++;
        } else {
            $this->updateCount++;
        }

        return $zaman;
    }

    public function rules(): array
    {
        return [
            'haftanin_gunu' => 'required|in:pazartesi,sali,carsamba,persembe,cuma,cumartesi,pazar',
            'baslangic_saati' => 'required|date_format:H:i',
            'bitis_saati' => 'required|date_format:H:i|after:baslangic_saati',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'haftanin_gunu.in' => 'Gün değeri geçersiz',
            'baslangic_saati.date_format' => 'Başlangıç saati biçimi HH:MM olmalıdır',
            'bitis_saati.after' => 'Bitiş saati başlangıçtan sonra olmalıdır',
        ];
    }

    public function onError(Throwable $e)
    {
        $this->errors[] = $e->getMessage();
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

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

    public function getErrors(): array
    {
        return $this->errors;
    }

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
