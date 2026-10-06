<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

final class DeleteTask
{
    public function execute(Task $task): void
    {
        DB::transaction(
            fn () => $task->delete()
        );
    }
}
