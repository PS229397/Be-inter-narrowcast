<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'customer_id',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (self $category): void {
            $category->slides()->update(['category_id' => null]);
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }
}

