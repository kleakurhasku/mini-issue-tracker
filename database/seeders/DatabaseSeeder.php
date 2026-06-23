<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Krijo dy përdorues demo
        $klea = User::factory()->create([
            'name' => 'Klea Kurhasku',
            'email' => 'klea.kurhasku@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $eraf = User::factory()->create([
            'name' => 'Eraf',
            'email' => 'eraf@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $users = [$klea, $eraf];

        // Krijo 10 tags
        $tags = Tag::factory(10)->create();

        // Krijo 5 projekte, secili me një pronar
        Project::factory(5)->create([
            'user_id' => fn () => $users[array_rand($users)]->id,
        ])->each(function ($project) use ($tags) {

            Issue::factory(rand(4, 8))->create([
                'project_id' => $project->id,
            ])->each(function ($issue) use ($tags) {

                $issue->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );

                Comment::factory(rand(0, 5))->create([
                    'issue_id' => $issue->id,
                ]);
            });
        });
    }
}