<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Slideshow extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'customer_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function slides(): BelongsToMany
    {
        return $this->belongsToMany(Slide::class, 'slideshow_slides')
            ->withPivot('sort_order');
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'slideshow_locations');
    }
}
