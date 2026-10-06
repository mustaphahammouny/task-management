<?php

use App\Actions\UpdateProject;
use App\Models\Project;
use App\Models\User;

test('updating a project persists its name and returns the project', function () {
    $project = Project::factory()->for(User::factory())->create(['name' => 'Original project']);

    $updatedProject = app(UpdateProject::class)->execute($project, ['name' => 'Updated project']);

    expect($updatedProject)->toBe($project);
    $this->assertDatabaseHas('projects', [
        'id' => $project->id,
        'name' => 'Updated project',
    ]);
});
