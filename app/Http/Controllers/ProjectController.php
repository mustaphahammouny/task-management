<?php

namespace App\Http\Controllers;

use App\Actions\CreateProject;
use App\Actions\DeleteProject;
use App\Actions\UpdateProject;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

final class ProjectController extends Controller
{
    public function __construct(
        #[CurrentUser] private readonly User $currentUser,
    ) {}

    public function store(
        StoreProjectRequest $request,
        CreateProject $createProject,
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $createProject->execute($this->currentUser, ['name' => $data['name']]);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Project created.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to create project.')]);
        }

        return back();
    }

    public function update(
        UpdateProjectRequest $request,
        Project $project,
        UpdateProject $updateProject,
    ): RedirectResponse {
        $data = $request->validated();

        try {
            $updateProject->execute($project, ['name' => $data['name']]);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Project updated.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to update project.')]);
        }

        return back();
    }

    public function destroy(
        Project $project,
        DeleteProject $deleteProject,
    ): RedirectResponse {
        try {
            $deleteProject->execute($project);

            Inertia::flash('toast', ['type' => 'success', 'message' => __('Project deleted.')]);
        } catch (\Throwable $th) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Failed to delete project.')]);
        }

        return back();
    }
}
