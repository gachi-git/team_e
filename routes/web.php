<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', fn () => view('welcome'));
Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');

// Auth required
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->middleware('verified')->name('dashboard');

    // 質問の作成/更新/削除はログイン必須
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit')->whereNumber('question');
    Route::patch('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update')->whereNumber('question');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy')->whereNumber('question');

    // 回答投稿 & ベストアンサー設定（ログイン必須）
    Route::post('/questions/{question}/answers', [QuestionController::class, 'storeAnswer'])->name('answers.store')->whereNumber('question');
    Route::post('/questions/{question}/answers/{answer}/best', [QuestionController::class, 'markBestAnswer'])->name('answers.best')->whereNumber('question')->whereNumber('answer');

    // プロフィール
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 質問詳細（公開）。静的パスと競合しないよう最後に＆数値制約
Route::get('/questions/{question}', [QuestionController::class, 'show'])
    ->name('questions.show')
    ->whereNumber('question');

require __DIR__.'/auth.php';
