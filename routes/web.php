<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TaskController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


//Project
Route::get("/project/list", [ProjectController::class, 'index'])->name("project.index");
Route::post("/project/store", [ProjectController::class, 'store'])->name("project.store");
Route::put("/project/{project}/update", [ProjectController::class, 'update'])->name("project.update");
Route::delete("/project/{project}/delete", [ProjectController::class, 'destroy'])->name("project.destroy");
Route::get("/project/{project}/show", [ProjectController::class, 'show'])->name("project.show");
Route::post("/project/{project}/addMember", [ProjectController::class, 'addMemberToProject'])->name("project.addMember");

//Board
Route::post("/project/{project}/board/store", [BoardController::class, 'store'])->name('board.store');
Route::put("/board/{board}/update", [BoardController::class, 'update'])->name('board.update');
Route::delete("/board/{board}/delete", [BoardController::class, 'destroy'])->name('board.destroy');
Route::get("/board/{board}/show", [BoardController::class, 'show'])->name('board.show');


//Column
//Route::get('/column', [ColumnController::class, 'index'])->name('column.index');
Route::get('/board/{board}/column/create', [ColumnController::class, 'create'])->name('column.create');
Route::post('/column', [ColumnController::class, 'store'])->name('column.store');
Route::get('/column/{column}/edit', [ColumnController::class, 'edit'])->name('column.edit');
Route::put('/column/{column}/update', [ColumnController::class, 'update'])->name('column.update');
Route::delete('/column/{column}/delete', [ColumnController::class, 'destroy'])->name('column.destroy');

//Task
Route::get('/task', [TaskController::class, 'index'])->name('task.index');
Route::get('board/{board}/column/{column}/task/create', [TaskController::class, 'create'])->name('task.create');
Route::post('/task', [TaskController::class, 'store'])->name('task.store');
Route::get('/task/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');
Route::put('/task/{task}/update', [TaskController::class, 'update'])->name('task.update');
Route::delete('/task/{task}/delete', [TaskController::class, 'destroy'])->name('task.destroy');

