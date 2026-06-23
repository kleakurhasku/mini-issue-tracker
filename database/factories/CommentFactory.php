<?php

namespace Database\Factories;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        $comments = [
            'I can take a look at this tomorrow.',
            'This is blocking my work, can we prioritize it?',
            'I think the issue is in the validation logic.',
            'Fixed in the latest commit, please review.',
            'We should add tests for this case.',
            'Any update on this one?',
            'Great work, looks good to me!',
            'Can you provide steps to reproduce?',
            'This needs more discussion before we start.',
        ];

        return [
            'issue_id' => Issue::factory(),
            'author_name' => fake()->firstName(),
            'body' => fake()->randomElement($comments),
        ];
    }
}