<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OgrenciGrubu extends Model
{
    use HasFactory;

    protected $table = 'ogrenci_gruplari';

    protected $fillable = [
        'isim',
        'yil',
        'ogrenci_sayisi',
        'ust_grup_id',
    ];

    public function ustGrup(): BelongsTo
    {
        return $this->belongsTo(OgrenciGrubu::class, 'ust_grup_id');
    }

    public function altGruplar(): HasMany
    {
        return $this->hasMany(OgrenciGrubu::class, 'ust_grup_id');
    }

    public function dersler(): BelongsToMany
    {
        return $this->belongsToMany(Ders::class, 'grup_dersleri', 'ogrenci_grup_id', 'ders_id');
    }

    public function kisitlamalar(): HasMany
    {
        return $this->hasMany(GrupKisitlama::class, 'ogrenci_grup_id');
    }
}
