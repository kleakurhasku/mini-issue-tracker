<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $names = [
            'Website Redesign',
            'Mobile App',
            'Billing System',
            'Customer Portal',
            'Marketing Campaign',
            'API Integration',
            'Internal Dashboard',
            'E-commerce Platform',
        ];

        $start = fake()->dateTimeBetween('-1 month', '+1 month');

        return [
            'name' => fake()->unique()->randomElement($names),
            'description' => fake()->sentence(10),
            'start_date' => $start,
            'deadline' => fake()->dateTimeBetween($start, '+3 months'),
        ];
    }
}