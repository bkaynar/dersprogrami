<?php

namespace App\Imports;

use App\Models\Ogretmen;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OgretmenMusaitlikAdvancedImport implements WithMultipleSheets
{
    protected $stats = ['success' => 0, 'errors' => 0];
    protected $errors = [];

    public function sheets(): array
    {
        $sheets = [];

        // Her sheet için import sınıfı oluştur
        $ogretmenler = Ogretmen::orderBy('isim')->get();

        foreach ($ogretmenler as $ogretmen) {
            $sheetName = preg_replace('/[^\w\s-]/', '', $ogretmen->isim);
            $sheetName = substr($sheetName, 0, 31);

            $sheets[$sheetName] = new OgretmenMusaitlikSheetImport($ogretmen, $this);
        }

        return $sheets;
    }

    public function getStats(): array
    {
        return $this->stats;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addSuccess(): void
    {
        $this->stats['success']++;
    }

    public function addError(string $error): void
    {
        $this->stats['errors']++;
        $this->errors[] = $error;
    }
}
