<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TrackerController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get(('/home'), function () {
    return view('home');
});

require __DIR__.'/auth.php';

Route::post('/tracker', [TrackerController::class, 'store'])->name('tracker.store');
Route::get('/tracker/{id}/edit', [TrackerController::class, 'edit'])->name('tracker.edit');
Route::put('/tracker/{id}', [TrackerController::class, 'update'])->name('tracker.update');
Route::delete('/tracker/{id}', [TrackerController::class, 'destroy'])->name('tracker.destroy');

Route::get('/allTransactions', [TrackerController::class, 'showforall'])
    ->middleware(['auth', 'verified'])
    ->name('allTransactions');


Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/categories', [CategoryController::class, 'index'])->name('editCategory');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/monthlySummary', [TrackerController::class, 'monthlySummary']  )->name('monthlySummary');
