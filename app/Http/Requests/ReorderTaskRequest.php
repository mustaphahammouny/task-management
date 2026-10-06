<?php

namespace App\Http\Requests;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReorderTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reordered_ids' => ['required', 'array'],
            'reordered_ids.*' => [
                'required',
                'integer',
                Rule::exists(Task::class, 'id')
                    ->where(
                        function (Builder $query) {
                            $query->whereIn(
                                'project_id',
                                Project::query()
                                    ->where('user_id', $this->user()->id)
                                    ->select('id')
                            );
                        }
                    ),
            ],
        ];
    }
}
