<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layout extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'orientation',
        'grid',
    ];

    protected function casts(): array
    {
        return [
            'grid' => 'array',
        ];
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'layout_customer');
    }
}

