<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisplayToken extends Model
{
    protected $fillable = [
        'customer_id',
        'token',
        'name',
        'expires_at',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isValid(): bool
    {
        return $this->expires_at->isFuture();
    }
}
