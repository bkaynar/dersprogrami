<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OgretmenDers extends Pivot
{
    protected $table = 'ogretmen_dersleri';
    public $incrementing = false;
    public $timestamps = false;
}
