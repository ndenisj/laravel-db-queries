<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'room_number' => $this->faker->unique()->numberBetween(100, 300),
            'room_size' => $this->faker->numberBetween(1, 5),
            'price' => $this->faker->numberBetween(100, 600),
            'description' => $this->faker->text(1000),
        ];
    }
}
