<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(LoginUserRequest $request): JsonResponse
    {
        try {
            [$user, $token] = $this->authService->authenticate($request->validated());

            return $this->successResponse(
                [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
                'Login successful',
                Response::HTTP_OK
            );
        } catch (AuthenticationException $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());

        return $this->successResponse(
            new UserResource($user),
            'Registration successful',
            Response::HTTP_CREATED
        );
    }

    public function logout(Request $request, User $user): JsonResponse
    {
        $this->authService->revokeTokens($user);

        return $this->successResponse(
            null,
            'Logout successful'
        );
    }
}
