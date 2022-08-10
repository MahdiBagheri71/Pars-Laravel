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

//user access api
Route::get('/me',[App\Http\Controllers\ParsApiController::class, 'getMe'])->middleware('token');
Route::get('/getUser/{user_id}',[App\Http\Controllers\ParsApiController::class, 'getUser'])->where('user_id', '[0-9]+')->middleware('token');
Route::get('/getAllUser',[App\Http\Controllers\ParsApiController::class, 'getAllUser'])->middleware('token');
Route::get('/getTasksMe',[App\Http\Controllers\ParsApiController::class, 'getTasksMe'])->middleware('token');
Route::get('/getTask/{id}',[App\Http\Controllers\ParsApiController::class, 'getTask'])->where('id', '[0-9]+')->middleware('token');
Route::get('/getTasksMeDate',[App\Http\Controllers\ParsApiController::class, 'getTasksMeDate'])->middleware('token');
Route::post('/setStatusTasks/{id}',[App\Http\Controllers\TasksApiController::class, 'setStatus'])->where('id', '[0-9]+')->middleware('token');


//admin acess api
Route::get('/getAllTasks',[App\Http\Controllers\ParsApiController::class, 'getAllTasks'])->middleware('admin');
Route::get('/getAllTasksDate',[App\Http\Controllers\ParsApiController::class, 'getAllTasksDate'])->middleware('admin');
Route::get('/getAllTasks/{user_id}',[App\Http\Controllers\ParsApiController::class, 'getAllTasksUser'])->where('user_id', '[0-9]+')->middleware('admin');
Route::post('/addTasks',[App\Http\Controllers\TasksApiController::class, 'add'])->middleware('admin');
Route::post('/updateTasks/{id}',[App\Http\Controllers\TasksApiController::class, 'update'])->where('id', '[0-9]+')->middleware('admin');
Route::delete('/deleteTasks/{id}',[App\Http\Controllers\TasksApiController::class, 'delete'])->where('id', '[0-9]+')->middleware('admin');
Route::get('/getTaskAdmin/{id}',[App\Http\Controllers\ParsApiController::class, 'getTaskAdmin'])->where('id', '[0-9]+')->middleware('admin');


//work by sanctum package
Route::post('/login',[App\Http\Controllers\Auth\UserApiController::class, 'login']);

Route::get('/user', [App\Http\Controllers\Auth\UserApiController::class, 'getUser'])->middleware('auth:sanctum');

Route::get('/user/logout', [App\Http\Controllers\Auth\UserApiController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user/tasks', [App\Http\Controllers\Auth\UserApiController::class, 'getTasks'])->middleware('auth:sanctum');
