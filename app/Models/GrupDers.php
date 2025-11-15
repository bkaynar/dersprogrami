<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupDers extends Model
{
    use HasFactory;

    protected $table = 'grup_dersleri';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ogrenci_grup_id',
        'ders_id',
    ];

    public function ogrenciGrubu()
    {
        return $this->belongsTo(OgrenciGrubu::class, 'ogrenci_grup_id');
    }

    public function ders()
    {
        return $this->belongsTo(Ders::class, 'ders_id');
    }
}
