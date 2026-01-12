<?php

namespace App\Exports;

use App\Models\Ogretmen;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OgretmenMusaitlikAdvancedTemplateExport implements WithMultipleSheets
{
    protected $ogretmenler;

    public function __construct()
    {
        $this->ogretmenler = Ogretmen::orderBy('isim')->get();
    }

    /**
     * Her öğretmen için ayrı sheet oluştur
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->ogretmenler as $ogretmen) {
            $sheets[] = new OgretmenMusaitlikSheetExport($ogretmen);
        }

        return $sheets;
    }
}
