<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function manage(User $user, ?Project $project = null): bool
    {
        return $user->isAdmin() ||
            ($user->isManager() && (! $project || $project->manager_id === $user->id));
    }

    public function view(User $user, Project $project): bool
    {
        return true;
    }

    public function create(User $user, ?Project $project = null): bool
    {
        return $user->isAdmin() || $user->isManager();
    }
}
