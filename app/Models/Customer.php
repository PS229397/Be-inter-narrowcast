<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class);
    }

    public function slideshows(): HasMany
    {
        return $this->hasMany(Slideshow::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function customComponents(): HasMany
    {
        return $this->hasMany(CustomComponent::class);
    }

    public function layouts(): BelongsToMany
    {
        return $this->belongsToMany(Layout::class, 'layout_customers');
    }
}
