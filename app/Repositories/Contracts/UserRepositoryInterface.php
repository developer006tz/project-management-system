<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): User;

    public function create(array $attributes): User;

    public function findById(int $id): User;

    public function update(User $user, array $attributes): bool;
}
