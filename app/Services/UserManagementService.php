<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class UserManagementService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function updateRole(int $userId, string $newRole, User $currentUser)
    {
        $targetUser = $this->userRepository->findById($userId);

        if (! $currentUser->isAdmin()) {
            throw new AuthorizationException('Only administrators can update user roles.');
        }

        if ($targetUser->id === $currentUser->id) {
            throw new AuthorizationException('Administrators cannot change their own role.');
        }

        $this->userRepository->update($targetUser, ['role' => $newRole]);

        return new UserResource($targetUser->refresh());
    }
}
