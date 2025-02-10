<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function authenticate(array $credentials): array
    {
        if (! Auth::attempt($credentials)) {
            throw new AuthenticationException('invalid credentials');
        }

        $user = $this->userRepository->findByEmail($credentials['email']);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [$user, $token];
    }

    public function register(array $userData): User
    {
        return $this->userRepository->create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role' => $userData['role'],
            'password' => Hash::make($userData['password']),
        ]);
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
