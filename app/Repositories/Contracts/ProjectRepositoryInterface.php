<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProjectRepositoryInterface
{
    public function create(array $attributes): Project;

    public function findById(int $id): Project;

    public function getAll(int $perPage = 10): LengthAwarePaginator;

    public function update(Project $project, array $attributes): bool;

    public function delete(Project $project): bool;
}
