<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\ProjectRepository;
use App\Repositories\Eloquent\TaskRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            fn () => new UserRepository(new User)
        );

        $this->app->bind(
            ProjectRepositoryInterface::class,
            fn () => new ProjectRepository(new Project)
        );

        $this->app->bind(
            TaskRepositoryInterface::class,
            fn () => new TaskRepository(new Task)
        );
    }
}
