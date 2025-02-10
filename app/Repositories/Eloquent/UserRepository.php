<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly User $model) {}

    public function findByEmail(string $email): User
    {
        return $this->model->where('email', $email)->firstOrFail();
    }

    public function create(array $attributes): User
    {
        return $this->model->create($attributes);
    }
}
