<?php

namespace App\Models;

use App\Enums\Orientation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'orientation',
        'customer_id',
    ];

    protected function casts(): array
    {
        return [
            'orientation' => Orientation::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function slideshows(): BelongsToMany
    {
        return $this->belongsToMany(Slideshow::class, 'slideshow_locations');
    }
}
