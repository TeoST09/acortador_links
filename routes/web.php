<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LinksAdminController;
use App\Http\Controllers\LinksController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::get('/link/{code}', [LinksController::class, 'redirect'])->name('links.redirect');
Route::post('/link/{code}/password', [LinksController::class, 'verifyPassword'])
    ->name('links.password.verify');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('/links', [LinksController::class, 'index'])->name('links.index');
    Route::get('/links/create', [LinksController::class, 'create'])->name('links.create');
    Route::post('/links', [LinksController::class, 'store'])->name('links.store');
    Route::get('/links/{link}/edit', [LinksController::class, 'edit'])->name('links.edit');
    Route::put('/links/{link}', [LinksController::class, 'update'])->name('links.update');
    Route::get('/links/{link}', [LinksController::class, 'show'])->name('links.show');
    Route::delete('/links/{link}', [LinksController::class, 'destroy'])->name('links.destroy');

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [LinksAdminController::class, 'index'])->name('admin');
        Route::inertia('/admin/links/create', 'links/admin/create')->name('admin.links.create');
        Route::get('/admin/links/{link}/edit', [LinksAdminController::class, 'edit']);
        Route::put('/admin/links/{link}', [LinksAdminController::class, 'update']);
        Route::post('/admin/links', [LinksAdminController::class, 'store']);
        Route::delete('/admin/links/{link}', [LinksAdminController::class, 'destroy']);
        Route::get('/admin/links/{link}', [LinksAdminController::class, 'show']);
    });

});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');

require __DIR__.'/settings.php';
