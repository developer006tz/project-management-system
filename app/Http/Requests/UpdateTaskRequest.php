<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');

        return $this->user()->can('update', $task);
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'project_id' => 'sometimes|exists:projects,id',
            'assignee_id' => [
                'sometimes',
                Rule::exists('users', 'id')->where('role', 'user'),
            ],
            'status' => 'sometimes|string|in:pending,in_progress,completed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The task name must be text only',

            'name.max' => 'The task name cannot exceed 255 characters',

            'description.string' => 'The task description must be text only',

            'project_id.exists' => 'The selected project does not exist',

            'assignee_id.exists' => 'The selected assignee does not exist or is not a regular user',

            'status.string' => 'The status must be text only',

            'status.in' => 'Status must be either pending, in progress, or completed',
        ];
    }
}
