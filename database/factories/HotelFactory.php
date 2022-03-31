<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => rtrim( ucfirst($this->faker->text(10)), '.' ),
            'description' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-20 days', '-10 days'),
            'updated_at' => $this->faker->dateTimeBetween('-5 days', '-1 days'),
        ];
    }
}
