<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LinksController;

Route::inertia('/', 'Welcome')->name('home');

Route::get('/link/{code}', [LinksController::class, 'redirect'])->name('links.redirect');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::get('/links', [LinksController::class, 'index'])->name('links.index');
    Route::get('/links/create', [LinksController::class, 'create'])->name('links.create');
    Route::post('/links', [LinksController::class, 'store'])->name('links.store');
    Route::get('/links/{link}/edit', [LinksController::class, 'edit'])->name('links.edit');
    Route::put('/links/{link}', [LinksController::class, 'update'])->name('links.update');
    Route::get('/links/{link}', [LinksController::class, 'show'])->name('links.show');
    Route::delete('/links/{link}', [LinksController::class, 'destroy'])->name('links.destroy');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');

require __DIR__.'/settings.php';
