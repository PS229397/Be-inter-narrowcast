<?php

namespace App\Models;

use App\Enums\Orientation;
use App\Support\Layouts\LayoutGrid;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeAvailableToCustomer(Builder $query, ?int $customerId): Builder
    {
        return $query->where(function (Builder $layoutQuery) use ($customerId): void {
            $layoutQuery->doesntHave('customers');

            if (filled($customerId)) {
                $layoutQuery->orWhereHas(
                    'customers',
                    fn (Builder $customerQuery) => $customerQuery->where('customers.id', $customerId),
                );
            }
        });
    }

    protected static function booted(): void
    {
        static::saving(function (self $layout): void {
            $layout->grid = LayoutGrid::normalize($layout->grid);
        });

        static::updating(function (self $layout): void {
            if ($layout->isDirty('orientation')) {
                $layout->orientation = $layout->getOriginal('orientation');
            }
        });
    }
}
