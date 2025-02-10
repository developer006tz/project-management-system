<?php

namespace App\Repositories\Contracts;

use App\Models\Project;

interface ProjectRepositoryInterface
{
    public function create(array $attributes): Project;

    public function findById(int $id): Project;

    public function getAll(array $filters = [], int $perPage = 10);

    public function update(Project $project, array $attributes): bool;

    public function delete(Project $project): bool;
}
