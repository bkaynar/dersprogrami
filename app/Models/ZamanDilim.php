<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZamanDilim extends Model
{
    use HasFactory;

    protected $table = 'zaman_dilimleri';

    public $timestamps = false; // migration doesn't add timestamps

    protected $fillable = [
        'haftanin_gunu',
        'baslangic_saati',
        'bitis_saati',
    ];

    public function grupKisitlamalari(): HasMany
    {
        return $this->hasMany(GrupKisitlama::class, 'zaman_dilimi_id');
    }

    public function ogretmenMusaitlik(): HasMany
    {
        return $this->hasMany(OgretmenMusaitlik::class, 'zaman_dilimi_id');
    }

    public function olusturulanProgramlar(): HasMany
    {
        return $this->hasMany(OlusturulanProgram::class, 'zaman_dilimi_id');
    }
}
