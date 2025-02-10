<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,user',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is mandatory',
            'name.string' => 'The name must be text only',
            'name.max' => 'The name cannot exceed 100 characters',

            'email.required' => 'Please provide an email address',
            'email.string' => 'The email must be text only',
            'email.email' => 'Please provide a valid email address',
            'email.max' => 'The email cannot exceed 100 characters',
            'email.unique' => 'User with this email has already been registered, please use another email',

            'password.required' => 'Password is mandatory for registration',
            'password.string' => 'The password must be text only',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match',

            'role.required' => 'Please select a user role',
            'role.string' => 'The role must be text only',
            'role.in' => 'Selected role must be either admin, manager, or user',
        ];
    }
}
