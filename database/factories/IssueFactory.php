<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'Fix login validation bug',
            'Add dark mode support',
            'Update API documentation',
            'Improve dashboard loading speed',
            'Fix mobile layout on checkout page',
            'Add email notifications',
            'Implement password reset flow',
            'Refactor payment service',
            'Add CSV export for reports',
            'Fix broken links in footer',
            'Optimize database queries',
            'Add user profile page',
            'Integrate Stripe payments',
            'Fix timezone display issue',
            'Add search functionality',
            'Improve error handling',
            'Update dependencies',
            'Add unit tests for auth module',
            'Fix image upload on Safari',
            'Redesign settings page',
        ];

        $descriptions = [
            'Users have reported issues with this. Needs investigation and a fix.',
            'This would improve the user experience significantly.',
            'Requested by the team during the last sprint planning.',
            'Currently causing problems in production. High priority.',
            'A nice-to-have improvement for the next release.',
            'Part of the ongoing performance optimization effort.',
        ];

        return [
            'project_id' => Project::factory(),
            'title' => fake()->randomElement($titles),
            'description' => fake()->randomElement($descriptions),
            'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+2 months'),
        ];
    }
}