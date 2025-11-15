<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OgretmenMusaitlik extends Model
{
    use HasFactory;

    protected $table = 'ogretmen_musaitlik';

    public $timestamps = false;

    protected $fillable = [
        'ogretmen_id',
        'zaman_dilimi_id',
        'musaitlik_tipi',
    ];

    public function ogretmen(): BelongsTo
    {
        return $this->belongsTo(Ogretmen::class, 'ogretmen_id');
    }

    public function zamanDilim(): BelongsTo
    {
        return $this->belongsTo(ZamanDilim::class, 'zaman_dilimi_id');
    }

    public function zamanDilimi(): BelongsTo
    {
        return $this->belongsTo(ZamanDilim::class, 'zaman_dilimi_id');
    }
}
