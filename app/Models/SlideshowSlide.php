<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SlideshowSlide extends Pivot
{
    protected $table = 'slideshow_slides';

    public $timestamps = false;
}
