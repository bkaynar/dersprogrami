<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OlusturulanProgram extends Model
{
    use HasFactory;

    protected $table = 'olusturulan_program';

    protected $fillable = [
        'akademik_donem',
        'ders_id',
        'ogretmen_id',
        'ogrenci_grup_id',
        'mekan_id',
        'zaman_dilimi_id',
    ];

    public function ders(): BelongsTo
    {
        return $this->belongsTo(Ders::class, 'ders_id');
    }

    public function ogretmen(): BelongsTo
    {
        return $this->belongsTo(Ogretmen::class, 'ogretmen_id');
    }

    public function ogrenciGrubu(): BelongsTo
    {
        return $this->belongsTo(OgrenciGrubu::class, 'ogrenci_grup_id');
    }

    public function mekan(): BelongsTo
    {
        return $this->belongsTo(Mekan::class, 'mekan_id');
    }

    public function zamanDilim(): BelongsTo
    {
        return $this->belongsTo(ZamanDilim::class, 'zaman_dilimi_id');
    }
}
