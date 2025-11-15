<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrupKisitlama extends Model
{
    use HasFactory;

    protected $table = 'grup_kisitlamalari';

    public $timestamps = false;

    protected $fillable = [
        'ogrenci_grup_id',
        'zaman_dilimi_id',
        'musait_mi',
    ];

    public function ogrenciGrubu(): BelongsTo
    {
        return $this->belongsTo(OgrenciGrubu::class, 'ogrenci_grup_id');
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
