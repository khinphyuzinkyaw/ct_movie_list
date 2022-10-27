<?php

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

// API route for login user
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

Route::get('/movies', [App\Http\Controllers\API\MovieController::class, 'index']);
Route::get('/movies/{id}', [App\Http\Controllers\API\MovieController::class, 'show']);

Route::middleware('auth:api')->group( function () {
  Route::post('/movies', [App\Http\Controllers\API\MovieController::class, 'store']);
  Route::post('/movies/{id}', [App\Http\Controllers\API\MovieController::class, 'update']);
  Route::delete('/movies/{id}', [App\Http\Controllers\API\MovieController::class, 'destory']);

  Route::post('/movies/{movie_id}/comments', [App\Http\Controllers\API\MovieController::class, 'storeComment']);
});