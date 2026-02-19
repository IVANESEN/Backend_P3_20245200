<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->numberBetween(1, 10);
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'isbn' => $this->faker->isbn13(),
            'total_copies' => $total,
            'available_copies' => $total,
            'status' => true,
        ];

    }
}
