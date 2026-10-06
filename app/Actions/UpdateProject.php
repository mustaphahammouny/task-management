<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

final class UpdateProject
{
    /**
     * @param  array{name: string}  $data
     */
    public function execute(Project $project, array $data): Project
    {
        return DB::transaction(
            function () use ($project, $data): Project {
                $project->update([
                    'name' => $data['name'],
                ]);

                return $project;
            }
        );
    }
}
