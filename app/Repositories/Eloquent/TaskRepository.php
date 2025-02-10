<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

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

    public function getProjectTasks(int $projectId, array $filters = [], int $perPage = 10)
    {
        $query = $this->model->where('project_id', $projectId)
            ->with(['assignee']);

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where(\DB::raw('LOWER(name)'), 'like', '%'.strtolower($filters['search']).'%')
                    ->orWhere(\DB::raw('LOWER(description)'), 'like', '%'.strtolower($filters['search']).'%');
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['assignee_id'])) {
            $query->where('assignee_id', $filters['assignee_id']);
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    public function update(Task $task, array $attributes): bool
    {
        return $task->update($attributes);
    }
}
