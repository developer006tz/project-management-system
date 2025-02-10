<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('manager')->paginate($perPage);
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
