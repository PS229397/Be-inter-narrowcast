<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'customer_id',
        'blade',
        'php',
        'scss',
        'js',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

