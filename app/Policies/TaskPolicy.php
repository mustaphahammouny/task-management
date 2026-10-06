<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return Task::query()
            ->whereRelation('project', 'user_id', $user->id)
            ->where('id', $task->id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return Task::query()
            ->whereRelation('project', 'user_id', $user->id)
            ->where('id', $task->id)
            ->exists();
    }
}
