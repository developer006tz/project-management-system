<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function createTask(StoreTaskRequest $request, $id): JsonResponse
    {
        $validatedData = $request->validated();
        $validatedData['project_id'] = $id;

        $task = $this->taskService->createTask($validatedData);

        return $this->successResponse(
            $task,
            'Task created successfully',
            Response::HTTP_CREATED
        );
    }

    public function viewProjectTasks(Request $request, $id): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $tasks = $this->taskService->getProjectTasks($id, $perPage);

        return $this->successResponse(
            $tasks,
            'Tasks retrieved successfully'
        );
    }

    public function updateTask(UpdateTaskRequest $request, $id): JsonResponse
    {
        $task = $this->taskService->updateTask($id, $request->validated());

        return $this->successResponse(
            $task,
            'Task updated successfully'
        );
    }

    public function markAsCompleted(UpdateTaskRequest $request, $id): JsonResponse
    {
        $task = $this->taskService->markAsCompleted($id);

        return $this->successResponse(
            $task,
            'Task marked as completed successfully'
        );
    }
}
