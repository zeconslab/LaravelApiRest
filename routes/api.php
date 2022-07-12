<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostsController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//User
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('logout', [AuthController::class, 'logout']);


//Posts
Route::middleware([JWTMiddleware::class])->group(function() {
    Route::get('posts', [PostsController::class, 'index']);
    Route::post('post/create', [PostsController::class, 'create']);
    Route::post('post/update/{id}', [PostsController::class, 'update']);
    Route::post('post/delete/{id}', [PostsController::class, 'delete']);
});

