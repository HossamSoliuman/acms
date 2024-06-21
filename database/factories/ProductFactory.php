<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'Tractor',
                'Plough',
                'Seeder',
                'Harvester',
                'Sprayer',
                'Baler',
                'Cultivator',
                'Mower',
                'Grain Cart',
                'Fertilizer Spreader'
            ]),
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 500, 50000),
            'is_active' => $this->faker->boolean,
            'cover' => $this->faker->imageUrl(640, 480, 'farm equipment', true, 'equipment'),
        ];
    }
}
