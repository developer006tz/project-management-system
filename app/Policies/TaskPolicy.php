<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function create(User $user, ?Task $task = null): bool
    {
        return $user->isManager();
    }

    public function update(User $user, ?Task $task = null): bool
    {
        return $user->isManager() || $user->isTaskAssignee($task);
    }
}
