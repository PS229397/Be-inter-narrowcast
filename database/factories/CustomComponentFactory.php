<?php

namespace Database\Factories;

use App\Models\CustomComponent;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomComponent>
 */
class CustomComponentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(2, true),
            'customer_id' => Customer::factory(),
            'blade' => '<div class="custom-component">Example</div>',
            'php' => '',
            'scss' => '',
            'js' => '',
        ];
    }
}
