<?php

namespace Database\Factories;

use App\Enums\Units;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->words(random_int(2, 5), true),
            'price' => random_int(10, 100) * fake()->randomElement([1_000, 2_000, 3_000, 5_000]),
            'amount' => fake()->boolean(70) ? random_int(50, 1_000) : 0,
            'unit' => fake()->randomElement(Units::values()),
        ];
    }
}
