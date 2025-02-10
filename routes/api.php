<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::group([], function ($auth) {
        $auth->post('/logout', [AuthController::class, 'logout']);
        $auth->post('/register', [AuthController::class, 'register']);
    });

    Route::group([], function ($admin) {
        $admin->post('/assign-role', [UserController::class,'assignRole']);
    });

});
