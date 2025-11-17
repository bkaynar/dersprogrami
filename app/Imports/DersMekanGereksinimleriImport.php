<?php

namespace App\Imports;

use App\Models\Ders;
use App\Models\DersMekanGereksinimi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;
use Throwable;

class DersMekanGereksinimleriImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable;

    protected $errors = [];
    protected $failures = [];
    protected $successCount = 0;
    protected $updateCount = 0;
    protected $createMissing = false;

    public function __construct(bool $createMissing = false)
    {
        $this->createMissing = $createMissing;
    }

    public function model(array $row)
    {
        $ders = Ders::where('ders_kodu', $row['ders_kodu'])->first();

        if (!$ders && $this->createMissing) {
            // Create a new Ders with ders_kodu as isim fallback
            $ders = Ders::create([
                'ders_kodu' => $row['ders_kodu'],
                'isim' => $row['ders_kodu'],
            ]);
        }

        if (!$ders) {
            // Return null to skip row; validation will capture this when createMissing is false
            return null;
        }

        $g = DersMekanGereksinimi::updateOrCreate(
            ['ders_id' => $ders->id, 'mekan_tipi' => $row['mekan_tipi']],
            ['gereksinim_tipi' => $row['gereksinim_tipi']]
        );

        if ($g->wasRecentlyCreated) {
            $this->successCount++;
        } else {
            $this->updateCount++;
        }

        return $g;
    }

    public function rules(): array
    {
        $rules = [
            'ders_kodu' => 'required|string',
            'mekan_tipi' => 'required|in:derslik,laboratuvar,konferans_salonu',
            'gereksinim_tipi' => 'required|in:zorunlu,olabilir',
        ];

        if (! $this->createMissing) {
            $rules['ders_kodu'] .= '|exists:dersler,ders_kodu';
        }

        return $rules;
    }

    public function customValidationMessages()
    {
        return [
            'ders_kodu.exists' => 'Ders kodu bulunamadı',
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
