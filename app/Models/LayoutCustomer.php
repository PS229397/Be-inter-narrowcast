<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class LayoutCustomer extends Pivot
{
    protected $table = 'layout_customers';

    public $timestamps = false;
}
