<?php

namespace App\Models;

use App\Enums\Orientation;
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
            'orientation' => Orientation::class,
            'grid' => 'array',
        ];
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'layout_customers');
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    protected static function booted(): void
    {
        static::updating(function (self $layout): void {
            if ($layout->isDirty('orientation')) {
                $layout->orientation = $layout->getOriginal('orientation');
            }
        });
    }
}
