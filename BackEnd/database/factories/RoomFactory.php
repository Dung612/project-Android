<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'name' => 'Phòng ' . $this->faker->unique()->numberBetween(100, 999),
            'room_type_id' => RoomType::factory(),
            'location' => 'Tầng ' . $this->faker->numberBetween(1, 5) . ', Nhà ' . $this->faker->randomElement(['A1', 'A2', 'B1', 'B2']),
            'capacity' => $this->faker->numberBetween(30, 200),
            'status' => $this->faker->randomElement(['available', 'maintenance']),
            'description' => $this->faker->optional()->sentence()
        ];
    }
} 