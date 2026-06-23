<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'bug',
                'feature',
                'urgent',
                'frontend',
                'backend',
                'documentation',
                'enhancement',
                'design',
                'security',
                'performance',
                'database',
                'api',
            ]),
            'color' => fake()->hexColor(),
        ];
    }
}