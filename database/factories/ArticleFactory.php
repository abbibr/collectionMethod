<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->unique()->sentence,
            'excerpt' => $this->faker->paragraph(8, true),
            'description' => $this->faker->paragraph(2, true),
            'min_to_read' => $this->faker->numberBetween(1, 10),
            'is_published' => $this->faker->boolean()
        ];
    }
}
