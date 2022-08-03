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
Route::get('/me',[App\Http\Controllers\ParsApiController::class, 'getMe'])->middleware('token');

Route::get('/getAllTasks',[App\Http\Controllers\ParsApiController::class, 'getAllTasks'])->middleware('token');
Route::get('/getAllTasks/{user_id}',[App\Http\Controllers\ParsApiController::class, 'getAllTasksUser'])->where('user_id', '[0-9]+')->middleware('token');
Route::get('/getUser/{user_id}',[App\Http\Controllers\ParsApiController::class, 'getUser'])->where('user_id', '[0-9]+')->middleware('token');
Route::get('/getAllUser',[App\Http\Controllers\ParsApiController::class, 'getAllUser'])->middleware('token');
