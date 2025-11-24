<?php

namespace App\Imports;

use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class ZamanDilimleriImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithBatchInserts
{
    use Importable;

    protected $errors = [];
    protected $failures = [];
    protected $successCount = 0;
    protected $updateCount = 0;

    public function model(array $row)
    {
        // Excel'den gelen saat formatını düzelt
        $baslangicSaati = $this->normalizeTime($row['baslangic_saati']);
        $bitisSaati = $this->normalizeTime($row['bitis_saati']);

        // Benzersiz anahtar: gün + başlama saati
        $zaman = ZamanDilim::updateOrCreate(
            ['haftanin_gunu' => $row['haftanin_gunu'], 'baslangic_saati' => $baslangicSaati],
            [
                'bitis_saati' => $bitisSaati,
            ]
        );

        if ($zaman->wasRecentlyCreated) {
            $this->successCount++;
        } else {
            $this->updateCount++;
        }

        return $zaman;
    }

    /**
     * Excel'den gelen saat formatını HH:MM formatına çevir
     */
    private function normalizeTime($time)
    {
        // Eğer zaten HH:MM formatındaysa direkt dön
        if (preg_match('/^\d{2}:\d{2}$/', $time)) {
            return $time;
        }

        // Eğer Excel date serial number ise (örn: 0.375 = 09:00)
        if (is_numeric($time) && $time < 1) {
            $hours = floor($time * 24);
            $minutes = round(($time * 24 - $hours) * 60);
            return sprintf('%02d:%02d', $hours, $minutes);
        }

        // Eğer Excel datetime formatındaysa (örn: "1900-01-01 09:00:00")
        if (preg_match('/^\d{4}-\d{2}-\d{2} (\d{2}:\d{2})/', $time, $matches)) {
            return $matches[1];
        }

        // Eğer "9:00" gibi tek haneli saat varsa
        if (preg_match('/^(\d{1,2}):(\d{2})$/', $time, $matches)) {
            return sprintf('%02d:%02d', $matches[1], $matches[2]);
        }

        // Diğer durumlar için olduğu gibi dön
        return $time;
    }

    public function rules(): array
    {
        return [
            'haftanin_gunu' => 'required|in:pazartesi,sali,carsamba,persembe,cuma,cumartesi,pazar',
            'baslangic_saati' => 'required', // Format kontrolünü normalizeTime yapacak
            'bitis_saati' => 'required',
        ];
    }

    public function batchSize(): int
    {
        return 1000;
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
