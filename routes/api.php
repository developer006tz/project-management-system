<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function ($router) {
    $router->post('/logout', [AuthController::class, 'logout']);
    $router->post('/register', [AuthController::class, 'register']);

});
