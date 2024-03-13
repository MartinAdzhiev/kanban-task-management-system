<?php

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
Route::get("/projects/list", [ProjectController::class, 'index'])->name("project.index");
Route::post("/projects/store", [ProjectController::class, 'store'])->name("project.store");
Route::put("/projects/{project}/update", [ProjectController::class, 'update'])->name("projects.update");
Route::delete("/projects/{project}/delete", [ProjectController::class, 'destroy'])->name("projects.destroy");
Route::get("/projects/{project}/show", [ProjectController::class, 'show'])->name("projects.show");
Route::post("/projects/{project}/addMember", [ProjectController::class, 'addMemberToProject'])->name("projects.addMember");
