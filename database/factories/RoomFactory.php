<?php

namespace Database\Factories;

use App\Models\RoomType;
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
            'name' => rtrim( ucfirst($this->faker->text(10)), '.' ),
            'description' => $this->faker->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-10 days', '-5 days'),
            'updated_at' => $this->faker->dateTimeBetween('-3 days', '-1 hour'),
            'room_type_id' => RoomType::factory()->create(),
        ];
    }
}
