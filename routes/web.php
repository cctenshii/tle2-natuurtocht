<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\NatuurDexController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NatuurDexController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/quiz/{id}', [\App\Http\Controllers\QuizController::class, 'showQuiz'])
    ->name('quiz')
    ->middleware(['auth', 'verified']);

//change up the route name
Route::post('/cards/shiny/{id}', [CardController::class, 'makeCardShiny'])
    ->name('cards.makeShiny')
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/natuur-dex', [NatuurDexController::class, 'index'])->name('natuur-dex.index');
    Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');

    Route::post('/cards/{card}/upload-photo', [PhotoController::class, 'store'])->name('cards.upload');
});

Route::get('/test-layout', function () {
    return view('test-layout');
});

require __DIR__ . '/auth.php';
