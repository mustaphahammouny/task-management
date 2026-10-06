<?php

namespace App\Http\Controllers;

use App\Actions\CreateTask;
use App\Actions\DeleteTask;
use App\Actions\ReorderTasks;
use App\Actions\UpdateTask;
use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\ReorderTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Inertia\Inertia;
use Inertia\Response;

final class TaskController extends Controller
{
    public function __construct(
        #[CurrentUser] private readonly User $currentUser,
    ) {}

    public function index(IndexTaskRequest $request): Response
    {
        $data = $request->validated();

        // callback is used to prevent loading projects each time (using only in inertia will not call this callback)
        $projectsCallback = function () {
            $projects = $this->currentUser->projects()->get();

            return ProjectResource::collection($projects);
        };

        // we can use pagination here
        $tasksCallback = function () use ($data) {
            $tasks = Task::query()
                ->withWhereRelation('project', 'user_id', $this->currentUser->id)
                ->when(
                    Arr::get($data, 'project_id'),
                    fn (Builder $query, int $projectId) => $query->where('project_id', $projectId)
                )
                ->orderBy('priority')
                ->get();

            return TaskResource::collection($tasks);
        };

        return Inertia::render('tasks/Index', [
            'projects' => $projectsCallback,
            'tasks' => $tasksCallback,
        ]);
    }

    public function store(
        StoreTaskRequest $request,
        CreateTask $createTask,
    ): RedirectResponse {
        $data = $request->validated();

        $project = $this->currentUser->projects()->whereKey($data['project_id'])->firstOrFail();

        try {
            $createTask->execute($project, [
                'name' => $data['name'],
                'priority' => $data['priority'],
            ]);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Task created.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to create task.')]);
        }

        return back();
    }

    public function update(
        UpdateTaskRequest $request,
        Task $task,
        UpdateTask $updateTask,
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $updateTask->execute($task, ['name' => $data['name']]);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Task updated.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to update task.')]);
        }

        return back();
    }

    public function reorder(
        ReorderTaskRequest $request,
        ReorderTasks $reorderTasks,
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $reorderTasks->execute($this->currentUser, ['reordered_ids' => $data['reordered_ids']]);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Tasks reordered.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to reorder tasks.')]);
        }

        return back();
    }

    public function destroy(
        Task $task,
        DeleteTask $deleteTask,
    ): RedirectResponse {
        try {
            $deleteTask->execute($task);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Task deleted.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to delete task.')]);
        }

        return back();
    }
}
