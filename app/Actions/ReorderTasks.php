<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class ReorderTasks
{
    public function execute(User $user, array $data): void
    {
        $reorderedIds = $data['reordered_ids'];

        $tasks = Task::query()
            ->whereRelation('project', 'user_id', $user->id)
            ->whereIn('id', $reorderedIds)
            ->get()
            ->keyBy('id');

        DB::transaction(
            function () use ($tasks, $reorderedIds) {
                foreach ($reorderedIds as $index => $id) {
                    $tasks->get($id)
                        ->update([
                            'priority' => $index + 1,
                        ]);
                }
            }
        );
    }
}
