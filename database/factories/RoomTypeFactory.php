<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'size' => $this->faker->numberBetween(1,5),
            'price' => $this->faker->numberBetween(100,500),
            'amount' => $this->faker->numberBetween(1,10),
            'created_at' => $this->faker->dateTimeBetween('-9 days', '-4 days'),
            'updated_at' => $this->faker->dateTimeBetween('-2 days', '-1 minute'),
        ];
    }
}
