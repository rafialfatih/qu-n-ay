<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(2);

        return [
            'id' => fake()->uuid(),
            'user_id' => random_int(1, 5),
            'title' => $title,
            'slug' => Str::slug($title),
            'question' => fake()->paragraph(),
        ];
    }
}
