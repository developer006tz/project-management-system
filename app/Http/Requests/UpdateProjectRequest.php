<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', Project::class);
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'manager_id' => [
                'sometimes',
                Rule::exists('users', 'id')->where('role', 'manager'),
            ],
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The project name must be text only',
            'name.max' => 'The project name cannot exceed 100 characters',

            'description.string' => 'The project description must be text only',

            'manager_id.exists' => 'The selected manager does not exist or does not have manager privileges',

            'start_date.date' => 'The start date must be a valid date',

            'end_date.date' => 'The end date must be a valid date',
            'end_date.after' => 'The end date must be after the start date',
        ];
    }
}
