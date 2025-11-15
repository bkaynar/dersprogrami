<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OgretmenDers extends Model
{
    use HasFactory;

    protected $table = 'ogretmen_dersleri';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ogretmen_id',
        'ders_id',
    ];

    public function ogretmen()
    {
        return $this->belongsTo(Ogretmen::class, 'ogretmen_id');
    }

    public function ders()
    {
        return $this->belongsTo(Ders::class, 'ders_id');
    }
}
