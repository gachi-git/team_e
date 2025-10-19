<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UniversityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;


Route::get('/', function(){
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::resource('questions', QuestionController::class)->only(['index','create','store']);

// Public API - accessible without authentication
Route::get('/api/universities/search', [UniversityController::class, 'search'])->name('universities.search');

Route::middleware(['auth'])->group(function () {
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/dashboard', fn () => view('dashboard'))->middleware('auth','verified')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::patch('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
});

Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');
Route::post('/questions/{question}/answers', [QuestionController::class, 'storeAnswer'])->name('answers.store');

Route::post(
    '/questions/{question}/answers/{answer}/best',
    [QuestionController::class, 'markBestAnswer']
)->middleware('auth')->name('answers.best');

require __DIR__.'/auth.php';
