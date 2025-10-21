<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DersMekanGereksinimi extends Model
{
    use HasFactory;

    protected $table = 'ders_mekan_gereksinimleri';

    public $timestamps = false;

    protected $fillable = [
        'ders_id',
        'mekan_tipi',
        'gereksinim_tipi',
    ];

    public function ders(): BelongsTo
    {
        return $this->belongsTo(Ders::class, 'ders_id');
    }
}
