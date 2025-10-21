<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mekan extends Model
{
    use HasFactory;

    protected $table = 'mekanlar';

    protected $fillable = [
        'isim',
        'kapasite',
        'mekan_tipi',
    ];

    public function olusturulanProgramlar(): HasMany
    {
        return $this->hasMany(OlusturulanProgram::class, 'mekan_id');
    }
}
