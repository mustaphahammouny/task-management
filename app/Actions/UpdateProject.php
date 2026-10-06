<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

final class UpdateProject
{
    public function execute(Project $project, array $data): Project
    {
        return DB::transaction(fn() => $project->update($data));
    }
}
