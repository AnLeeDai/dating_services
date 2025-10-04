<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\DatingController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/user', [UserController::class, 'getUser']);
Route::get('/user/{id}', [UserController::class, 'getUserById']);
Route::post('/upload-avatar', [UserController::class, 'uploadAvatar']);

Route::get('/dating-duration', [DatingController::class, 'getDuration']);
