<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ProfileController;

Route::get('/', function(){
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::resource('questions', QuestionController::class)->only(['index','create','store']);

Route::middleware(['auth'])->group(function () {
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/dashboard', fn () => view('dashboard'))->middleware('auth','verified')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('questions.show');

Route::post('/questions/{question}/answers', [QuestionController::class, 'storeAnswer'])
    ->name('answers.store');


require __DIR__.'/auth.php';
