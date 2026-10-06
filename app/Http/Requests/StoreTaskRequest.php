<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'integer', 'min:1'],
            'project_id' => [
                'required',
                'integer',
                Rule::exists(Project::class, 'id')
                    ->where('user_id', $this->user()->id),
            ],
        ];
    }
}
