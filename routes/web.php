<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::resource('projects', ProjectController::class)->only(['store', 'update', 'destroy']);
});

require __DIR__ . '/settings.php';
