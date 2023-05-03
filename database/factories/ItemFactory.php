<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        
        return [
            'name' => $name,
            'name_display' => rand(0, 1) ? explode(' ', $name)[0] : null,
            'price' => fake()->randomFloat(2, 1, 500),
            'sku' => fake()->words(2, true),
            'status' => rand(1, 3),
            'notes' => rand(1, 2),
        ];
    }
}
