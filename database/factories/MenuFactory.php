<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(2, true);
        $date = fake()->dateTimeBetween('-1 week');

        return [
            'name' => $name,
            'name_display' => rand(0, 1) ? explode(' ', $name)[0] : null,
            'status' => fake()->numberBetween(1, 2),
            'created_at' => $date,
            'updated_at' => $date
        ];
    }
}
