<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Krijo 10 tags
        $tags = Tag::factory(10)->create();

        // Krijo 5 projekte
        Project::factory(5)->create()->each(function ($project) use ($tags) {

            // Çdo projekt merr 4-8 issue
            Issue::factory(rand(4, 8))->create([
                'project_id' => $project->id,
            ])->each(function ($issue) use ($tags) {

                // Çdo issue merr 1-3 tags të rastësishëm
                $issue->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Çdo issue merr 0-5 komente
                Comment::factory(rand(0, 5))->create([
                    'issue_id' => $issue->id,
                ]);
            });
        });
    }
}