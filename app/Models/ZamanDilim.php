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

    protected $appends = [
        'gun',
        'gun_sirasi',
        'baslangic_saat',
        'bitis_saat',
    ];

    // Accessor'lar - eski kod ile uyumluluk için
    public function getGunAttribute(): string
    {
        return $this->haftanin_gunu;
    }

    public function getGunSirasiAttribute(): int
    {
        $gunler = [
            'pazartesi' => 0,
            'sali' => 1,
            'carsamba' => 2,
            'persembe' => 3,
            'cuma' => 4,
            'cumartesi' => 5,
            'pazar' => 6,
        ];

        return $gunler[strtolower($this->haftanin_gunu)] ?? 0;
    }

    public function getBaslangicSaatAttribute(): string
    {
        return $this->baslangic_saati;
    }

    public function getBitisSaatAttribute(): string
    {
        return $this->bitis_saati;
    }

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
