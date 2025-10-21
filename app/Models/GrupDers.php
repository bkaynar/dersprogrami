<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GrupDers extends Pivot
{
    protected $table = 'grup_dersleri';
    public $incrementing = false;
    public $timestamps = false;
}
