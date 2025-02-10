<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Services\UserManagementService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserManagementService $userManagementService
    ) {}
    public function assignRole(UpdateUserRoleRequest $request): JsonResponse
    {
        try {
            $user = $this->userManagementService->updateRole(
                $request->validated('user_id'),
                $request->validated('role'),
                $request->user()
            );

            return $this->successResponse(
                $user,
                'Role updated successfully',
                Response::HTTP_OK
            );
        } catch (AuthorizationException $e) {
            return $this->errorResponse(
                $e->getMessage(),
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
