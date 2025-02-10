<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViewAllTasksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,in_progress,completed',
            'assignee_id' => 'nullable|exists:users,id',
            'sort_by' => 'nullable|in:name,created_at,status',
            'sort_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
