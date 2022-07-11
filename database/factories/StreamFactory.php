<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stream>
 */
class StreamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->unique()->name(),
            'import_id' => Str::random(10),
            'game_id' => Str::random(10),
            'game_name' => fake()->unique()->name(),
            'user_id' => Str::random(10),
            'user_name' => fake()->unique()->userName(),
            'viewer_count' => fake()->unique()->numberBetween(1, 1000),
            'started_at' => fake()->dateTime(),
        ];
    }
}
