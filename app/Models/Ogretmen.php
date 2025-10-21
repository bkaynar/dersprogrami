<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ogretmen extends Model
{
    use HasFactory;

    protected $table = 'ogretmenler';

    protected $fillable = [
        'isim',
        'unvan',
        'email',
    ];

    public function dersler(): BelongsToMany
    {
        return $this->belongsToMany(Ders::class, 'ogretmen_dersleri', 'ogretmen_id', 'ders_id');
    }

    public function musaitliklar(): HasMany
    {
        return $this->hasMany(OgretmenMusaitlik::class, 'ogretmen_id');
    }

    public function olusturulanProgramlar(): HasMany
    {
        return $this->hasMany(OlusturulanProgram::class, 'ogretmen_id');
    }
}
