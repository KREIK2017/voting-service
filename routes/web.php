<?php

use App\Http\Controllers\Admin\OptionController as AdminOptionController;
use App\Http\Controllers\Admin\PollController as AdminPollController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/polls', [VoteController::class, 'index'])->name('polls.index');
    Route::get('/polls/{poll}', [VoteController::class, 'show'])->name('polls.show');
    Route::post('/polls/{poll}/vote', [VoteController::class, 'vote'])->name('polls.vote');
    Route::get('/polls/{poll}/results', [VoteController::class, 'results'])->name('polls.results');
    Route::get('/my-votes', [VoteController::class, 'myVotes'])->name('votes.my');
});

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('polls', AdminPollController::class);

        Route::get('polls/{poll}/votes', [AdminPollController::class, 'votes'])
            ->name('polls.votes');

        Route::get('polls/{poll}/options/create', [AdminOptionController::class, 'create'])
            ->name('polls.options.create');
        Route::post('polls/{poll}/options', [AdminOptionController::class, 'store'])
            ->name('polls.options.store');
        Route::get('options/{option}/edit', [AdminOptionController::class, 'edit'])
            ->name('options.edit');
        Route::put('options/{option}', [AdminOptionController::class, 'update'])
            ->name('options.update');
        Route::delete('options/{option}', [AdminOptionController::class, 'destroy'])
            ->name('options.destroy');
    });

require __DIR__.'/auth.php';
