<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\User;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\ProjectRepository;
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
    }
}
