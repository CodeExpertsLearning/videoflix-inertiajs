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

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', \App\Http\Controllers\Actions\DashboardAction::class)
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'user.is.admin'])->group(function () {

    Route::resource('contents', \App\Http\Controllers\Media\ContentController::class);

    Route::get('/contents/{content}/videos/upload', [\App\Http\Controllers\Media\VideoController::class, 'upload'])->name('videos.upload');
    Route::post('/{content}/videos', [\App\Http\Controllers\Media\VideoController::class, 'store'])->name('videos.store');
    Route::post('/contents/{content}/videos/{video}/process', [\App\Http\Controllers\Media\VideoController::class, 'process'])->name('videos.upload.process');
    Route::delete('/videos/{video}', [\App\Http\Controllers\Media\VideoController::class, 'destroy'])->name('videos.destroy');

    Route::match(['PUT', 'PATCH'], '/contents/{content}/videos/{video}', [\App\Http\Controllers\Media\VideoController::class, 'update'])->name('videos.update');
});

Route::get('/watch/{content:slug}', \App\Http\Controllers\Actions\PlayerAction::class)
    ->middleware(['auth'])
    ->name('video.player');


Route::get('resources/{code}/{video}', function ($code, $video = null) {
    $video = $code . '/' . $video;

    return \Illuminate\Support\Facades\Storage::disk('videos_processed')
        ->response(
            $video,
            null,
            [
                'Content-Type' => 'application/x-mpegURL',
                'isHls' => true
            ]
        );
})->name('stream_player')->middleware(['auth']);
