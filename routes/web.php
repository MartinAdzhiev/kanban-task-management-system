<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ColumnController;

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


//Column
//Route::get('/column', [ColumnController::class, 'index'])->name('column.index');
Route::get('/board/{board}/column/create', [ColumnController::class, 'create'])->name('column.create');
Route::post('/column', [ColumnController::class, 'store'])->name('column.store');
Route::get('/column/{column}/edit', [ColumnController::class, 'edit'])->name('column.edit');
Route::put('/column/{column}/update', [ColumnController::class, 'update'])->name('column.update');
Route::delete('/column/{column}/delete', [ColumnController::class, 'destroy'])->name('column.destroy');
