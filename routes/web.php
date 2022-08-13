<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');//change to dashboard by Mahdi
Route::get('/tasksCalendar', [App\Http\Controllers\TasksController::class, 'tasksCalendar'])->name('tasksCalendar');//change by Mahdi
Route::get('/tasksList', [App\Http\Controllers\TasksController::class, 'tasksList'])->name('tasksList');//change by Mahdi

// Route::get('/fullcalendar', function () {//by Mahdi
//     return view('fullcalendar');
// });
