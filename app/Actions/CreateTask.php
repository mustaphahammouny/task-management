<?php

namespace App\Actions;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

final class CreateTask
{
    public function execute(Project $project, array $data): Task
    {
        return DB::transaction(
            fn () => $project->tasks()
                ->create([
                    'name' => $data['name'],
                    'priority' => 1,
                ])
        );
    }
}
