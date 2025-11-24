<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Ders extends Model
{
    use HasFactory;

    protected $table = 'dersler';

    protected $fillable = [
        'ders_kodu',
        'isim',
        'haftalik_saat',
    ];

    public function mekanGereksinimi(): HasOne
    {
        return $this->hasOne(DersMekanGereksinimi::class, 'ders_id');
    }

    public function ogrenciGruplari(): BelongsToMany
    {
        return $this->belongsToMany(OgrenciGrubu::class, 'grup_dersleri', 'ders_id', 'ogrenci_grup_id');
    }

    public function ogretmenler(): BelongsToMany
    {
        return $this->belongsToMany(Ogretmen::class, 'ogretmen_dersleri', 'ders_id', 'ogretmen_id');
    }

    public function olusturulanProgramlar(): HasMany
    {
        return $this->hasMany(OlusturulanProgram::class, 'ders_id');
    }
}
