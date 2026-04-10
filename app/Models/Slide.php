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
        'slide_content',
        'is_active',
        'start_date',
        'end_date',
        'duration_in_seconds',
    ];

    protected function casts(): array
    {
        return [
            'slide_content' => 'array',
            'is_active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
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
        return $this->belongsToMany(Slideshow::class, 'slideshow_slides')
            ->withPivot('sort_order');
    }
}
