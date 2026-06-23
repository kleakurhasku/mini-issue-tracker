<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');

        return [
            'name' => fake()->catchPhrase(),
            'description' => fake()->paragraph(),
            'start_date' => $start,
            'deadline' => fake()->dateTimeBetween($start, '+3 months'),
        ];
    }
}