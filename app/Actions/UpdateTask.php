<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

final class UpdateTask
{
    /**
     * @param  array{name: string}  $data
     */
    public function execute(Task $task, array $data): Task
    {
        return DB::transaction(
            function () use ($task, $data): Task {
                $task->update([
                    'name' => $data['name'],
                ]);

                return $task;
            }
        );
    }
}
