<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('jwt.verify')->group(function () {
    Route::get('/tasks', [TaskController::class, 'getUserTasks']);
    Route::post('/tasks', [TaskController::class, 'createTask']);
    Route::put('/tasks/{id}', [TaskController::class, 'updateTask']);
    Route::delete('/tasks/{id}', [TaskController::class, 'deleteTask']);
});
