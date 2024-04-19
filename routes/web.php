<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'user.is.admin'])->group(function () {

    Route::resource('contents', \App\Http\Controllers\Media\ContentController::class);

    Route::get('/contents/{content}/videos/upload', [\App\Http\Controllers\Media\VideoController::class, 'upload'])->name('videos.upload');
    Route::post('/{content}/videos', [\App\Http\Controllers\Media\VideoController::class, 'store'])->name('videos.store');
    Route::post('/contents/{content}/videos/{video}/process', [\App\Http\Controllers\Media\VideoController::class, 'process'])->name('videos.upload.process');
    Route::delete('/videos/{video}', [\App\Http\Controllers\Media\VideoController::class, 'destroy'])->name('videos.destroy');

    Route::match(['PUT', 'PATCH'], '/contents/{content}/videos/{video}', [\App\Http\Controllers\Media\VideoController::class, 'update'])->name('videos.update');
});
