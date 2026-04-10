<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'layout_id',
        'customer_id',
        'category_id',
        'is_active',
        'start_date',
        'end_date',
        'duration_in_seconds',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(Layout::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function slideshows(): BelongsToMany
    {
        return $this->belongsToMany(Slideshow::class, 'slideshow_slide')
            ->withPivot('sort_order');
    }
}

