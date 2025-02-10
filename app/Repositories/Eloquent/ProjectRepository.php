<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(private readonly Project $model) {}

    public function create(array $attributes): Project
    {
        return $this->model->create($attributes);
    }

    public function findById(int $id): Project
    {
        return $this->model->findOrFail($id);
    }

    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->with('manager');

        $user = request()->user();

        if ($user->isUser()) {
            $query = $this->model->whereUserHasTasks($user->id)->with('manager');
        }

        if (! empty($filters['search'])) {
            $query->where(\DB::raw('LOWER(name)'), 'like', '%'.strtolower($filters['search']).'%')
                ->orWhere(\DB::raw('LOWER(description)'), 'like', '%'.strtolower($filters['search']).'%');
        }

        if (! empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->where('start_date', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->where('end_date', '<=', $filters['end_date']);
        }

        $sortField = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    public function update(Project $project, array $attributes): bool
    {
        return $project->update($attributes);
    }

    public function delete(Project $project): bool
    {
        return $project->delete();
    }
}
