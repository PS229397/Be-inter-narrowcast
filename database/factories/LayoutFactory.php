<?php

namespace Database\Factories;

use App\Enums\Orientation;
use App\Models\Layout;
use App\Support\Layouts\LayoutGrid;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Layout>
 */
class LayoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'orientation' => fake()->randomElement(Orientation::cases()),
            'grid' => LayoutGrid::empty(),
        ];
    }
}
