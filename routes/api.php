<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentsController;
use App\Http\Controllers\Api\LikesController;
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
    Route::post('post/update', [PostsController::class, 'update']);
    Route::post('post/delete', [PostsController::class, 'delete']);

    Route::get('post/comments', [CommentsController::class, 'index']);
    Route::post('comment/create', [CommentsController::class, 'create']);
    Route::post('comment/update', [CommentsController::class, 'update']);
    Route::post('comment/delete', [CommentsController::class, 'delete']);

    Route::post('post/like', [LikesController:: class, 'index']);

    Route::post('profile', [AuthController::class, 'profile']);
});

