<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::group([], function ($auth) {
        $auth->post('/logout', [AuthController::class, 'logout']);
        $auth->post('/register', [AuthController::class, 'register']);
    });

    Route::group([], function ($admin) {
        $admin->post('/assign-role', [UserController::class, 'assignRole']);
    });

    Route::group([], function ($project) {
        $project->post('/projects', [ProjectController::class, 'createProject'])->middleware('role:admin,manager');
        $project->get('/projects', [ProjectController::class, 'viewAllProjects']);
        $project->get('/projects/{id}', [ProjectController::class, 'viewSingleProject']);
        $project->put('/projects/{id}', [ProjectController::class, 'updateProject'])->middleware('role:admin,manager');
        $project->delete('/projects/{id}', [ProjectController::class, 'deleteProject'])->middleware('role:admin,manager');
    });

    Route::group([], function ($task) {
        $task->post('/projects/{id}/tasks', [TaskController::class, 'createTask'])->middleware('role:manager');
        $task->get('/projects/{id}/tasks', [TaskController::class, 'viewProjectTasks']);
        $task->put('/tasks/{id}', [TaskController::class, 'updateTask'])->middleware('role:manager');
        $task->patch('/tasks/{id}/status', [TaskController::class, 'markAsCompleted']);
    });

    Route::group([], function ($dashboard) {
        $dashboard->get('/dashboard', [DashboardController::class, 'viewSystemSummary']);
    });

});
