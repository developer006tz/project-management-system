<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(private readonly Task $model) {}

    public function create(array $attributes): Task
    {
        return $this->model->create($attributes);
    }

    public function findById(int $id): Task
    {
        return $this->model->findOrFail($id);
    }

    public function getProjectTasks(int $projectId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->where('project_id', $projectId)
            ->with(['assignee'])
            ->paginate($perPage);
    }

    public function update(Task $task, array $attributes): bool
    {
        return $task->update($attributes);
    }
}
