<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::all()->each(
            fn (Project $project) => Task::factory()
                ->for($project)
                ->count(rand(1, 10))
                ->state(new Sequence(
                    fn (Sequence $sequence) => ['priority' => $sequence->index + 1],
                ))
                ->create()
        );
    }
}
