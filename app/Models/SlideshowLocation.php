<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SlideshowLocation extends Pivot
{
    protected $table = 'slideshow_locations';

    public $timestamps = false;
}
