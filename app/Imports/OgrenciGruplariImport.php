<?php

namespace App\Imports;

use App\Models\OgrenciGrubu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class OgrenciGruplariImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable;

    protected $errors = [];
    protected $failures = [];
    protected $successCount = 0;
    protected $updateCount = 0;

    public function model(array $row)
    {
        // Üst grup adı varsa bul, yoksa null
        $ustGrupId = null;

        if (!empty($row['ust_grup_adi'])) {
            $ust = OgrenciGrubu::where('isim', $row['ust_grup_adi'])->first();
            if ($ust) {
                $ustGrupId = $ust->id;
            }
        }

        $grup = OgrenciGrubu::updateOrCreate(
            ['isim' => $row['isim'], 'yil' => (int) $row['yil']],
            [
                'ogrenci_sayisi' => (int) ($row['ogrenci_sayisi'] ?? 0),
                'ust_grup_id' => $ustGrupId,
            ]
        );

        if ($grup->wasRecentlyCreated) {
            $this->successCount++;
        } else {
            $this->updateCount++;
        }

        return $grup;
    }

    public function rules(): array
    {
        return [
            'isim' => 'required|string|max:255',
            'yil' => 'required|integer|min:1|max:6',
            'ogrenci_sayisi' => 'required|integer|min:1',
            'ust_grup_adi' => 'nullable|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'isim.required' => 'Grup adı zorunludur',
            'yil.required' => 'Yıl zorunludur',
            'yil.integer' => 'Yıl sayı olmalıdır',
            'yil.min' => 'Yıl en az 1 olabilir',
            'yil.max' => 'Yıl en fazla 6 olabilir',
            'ogrenci_sayisi.required' => 'Öğrenci sayısı zorunludur',
            'ogrenci_sayisi.integer' => 'Öğrenci sayısı sayı olmalıdır',
            'ogrenci_sayisi.min' => 'Öğrenci sayısı en az 1 olmalıdır',
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
