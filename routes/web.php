<?php

use App\Http\Controllers\Admin\PollController as AdminPollController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Models\Poll;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $polls = Poll::with('options')
        ->where('is_active', true)
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', compact('polls'));
})->name('home');

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])
    ->name('locale.switch');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('polls', AdminPollController::class);
    });

require __DIR__.'/auth.php';
