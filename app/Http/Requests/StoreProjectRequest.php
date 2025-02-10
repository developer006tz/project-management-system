<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Project::class);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'manager_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'manager'),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a name for the project',
            'name.string' => 'The project name must be text only',
            'name.max' => 'The project name cannot exceed 100 characters',

            'description.required' => 'Please provide a description for the project',
            'description.string' => 'The project description must be text only',

            'manager_id.required' => 'Please select a project manager',
            'manager_id.exists' => 'The selected manager does not exist or does not have manager privileges',

            'start_date.required' => 'Please specify when the project will start',
            'start_date.date' => 'The start date must be a valid date',

            'end_date.required' => 'Please specify when the project will end',
            'end_date.date' => 'The end date must be a valid date',
            'end_date.after' => 'The end date must be after the start date',
        ];
    }
}
