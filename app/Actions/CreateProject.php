<?php

namespace App\Actions;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class CreateProject
{
    /**
     * @param  array{name: string}  $data
     */
    public function execute(User $user, array $data): Project
    {
        return DB::transaction(
            fn () => $user->projects()
                ->create([
                    'name' => $data['name'],
                ])
        );
    }
}
