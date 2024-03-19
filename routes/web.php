<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
