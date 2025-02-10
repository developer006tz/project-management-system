<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Requests\ViewAllProjectsRequest;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct(
        private readonly ProjectService $projectService
    ) {}

    public function createProject(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->projectService->createProject($request->validated());

        return $this->successResponse(
            $project,
            'Project created successfully',
            Response::HTTP_CREATED
        );
    }

    public function viewAllProjects(ViewAllProjectsRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $perPage = $request->input('per_page', 10);
        $projects = $this->projectService->getAllProjects($filters, $perPage);

        return $this->successResponse(
            $projects,
            'Projects retrieved successfully'
        );
    }

    public function viewSingleProject(Request $request, $id): JsonResponse
    {
        $project = $this->projectService->getProject($id);

        return $this->successResponse(
            $project,
            'Project retrieved successfully'
        );
    }

    public function updateProject(UpdateProjectRequest $request, $id): JsonResponse
    {
        $project = $this->projectService->updateProject($id, $request->validated());

        return $this->successResponse(
            $project,
            'Project updated successfully'
        );
    }

    public function deleteProject(Request $request, $id): JsonResponse
    {
        $this->projectService->deleteProject($id);

        return $this->successResponse(
            null,
            'Project deleted successfully'
        );
    }
}
