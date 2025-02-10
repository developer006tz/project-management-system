<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskRepositoryInterface
{
    public function create(array $attributes): Task;

    public function findById(int $id): Task;

    public function getProjectTasks(int $projectId, int $perPage = 10): LengthAwarePaginator;

    public function update(Task $task, array $attributes): bool;
}
