<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'role' => 'sometimes|string|in:admin,manager,user',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'A user ID is required',
            'user_id.exists' => 'The selected user does not exist',

            'role.string' => 'The role must be text only',
            'role.in' => 'Selected role must be either admin, manager, or user',
        ];
    }
}
