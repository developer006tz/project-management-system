<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Task::class);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'assignee_id' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'user'),
            ],
            'status' => 'required|string|in:pending,in_progress,completed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide a name for the task',
            'name.string' => 'The task name must be text only',
            'name.max' => 'The task name cannot exceed 255 characters',

            'description.required' => 'Please provide a description for the task',
            'description.string' => 'The task description must be text only',

            'assignee_id.required' => 'Please select an assignee for this task',
            'assignee_id.exists' => 'The selected assignee does not exist or is not a regular user',

            'status.required' => 'Please specify the task status',
            'status.string' => 'The status must be text only',
            'status.in' => 'Status must be either pending, in progress, or completed',
        ];
    }
}
