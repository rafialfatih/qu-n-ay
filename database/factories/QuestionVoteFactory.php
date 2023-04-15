<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionVoteFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => random_int(1,5),
            'question_id' => Question::factory(),
            'vote' => 'up',
        ];
    }
}
