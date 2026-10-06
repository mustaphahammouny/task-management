<?php

namespace App\Http\Controllers;

use App\Actions\CreateTask;
use App\Actions\DeleteTask;
use App\Actions\UpdateTask;
use App\Http\Requests\IndexTaskRequest;
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

final class taskController extends Controller
{
    public function __construct(
        #[CurrentUser] private readonly User $currentUser,
    ) {}

    public function index(IndexTaskRequest $request): Response
    {
        $data = $request->validated();

        $projectsCallback = function () {
            $projects = $this->currentUser->projects()->get();

            return ProjectResource::collection($projects);
        };

        $tasksCallback = function () use ($data) {
            $tasks = Task::query()
                ->withWhereRelation('project', 'user_id', $this->currentUser->id)
                ->when(
                    Arr::get($data, 'project_id'),
                    fn (Builder $query, int $projectId) => $query->where('project_id', $projectId)
                )
                ->orderBy('priority', 'asc')
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

        $project = $this->currentUser->projects()->findOrFail($data['project_id']);

        try {
            $createTask->execute($project, $data);

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
            $updateTask->execute($task, $data);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Task updated.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to update task.')]);
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
