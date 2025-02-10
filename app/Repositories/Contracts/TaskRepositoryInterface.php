<?php

namespace App\Repositories\Contracts;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function create(array $attributes): Task;

    public function findById(int $id): Task;

    public function getProjectTasks(int $projectId, array $filters = [], int $perPage = 10);

    public function update(Task $task, array $attributes): bool;
}
