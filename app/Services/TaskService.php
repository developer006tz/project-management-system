<?php

namespace App\Services;

use App\Http\Resources\TaskResource;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {}

    public function createTask(array $data): TaskResource
    {
        $task = $this->taskRepository->create($data);

        return new TaskResource($task);
    }

    public function getProjectTasks(int $projectId, int $perPage = 10)
    {
        return $this->taskRepository->getProjectTasks($projectId, $perPage);
    }

    public function updateTask(int $id, array $data): TaskResource
    {
        $task = $this->taskRepository->findById($id);
        $this->taskRepository->update($task, $data);

        return new TaskResource($task->refresh());
    }

    public function markAsCompleted(int $id): TaskResource
    {
        $task = $this->taskRepository->findById($id);
        $this->taskRepository->update($task, ['status' => 'completed']);

        return new TaskResource($task->refresh());
    }
}
