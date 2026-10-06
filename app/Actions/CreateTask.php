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
            function () use ($project, $data) {
                $tasksToReorder = Task::query()
                    ->whereRelation('project', 'user_id', $project->user_id)
                    ->where('priority', '>=', $data['priority'])
                    ->orderBy('priority')
                    ->get();

                $task = $project->tasks()
                    ->create([
                        'name' => $data['name'],
                        'priority' => $data['priority'],
                    ]);

                foreach ($tasksToReorder as $index => $taskToReorder) {
                    $taskToReorder->update([
                        'priority' => $data['priority'] + $index + 1,
                    ]);
                }

                return $task;
            }
        );
    }
}
