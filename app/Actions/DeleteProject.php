<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

final class DeleteProject
{
    public function execute(Project $project): void
    {
        DB::transaction(
            fn () => $project->delete()
        );
    }
}
