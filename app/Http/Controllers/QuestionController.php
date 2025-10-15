<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::latest()->get();
        return view('questions.index', compact('questions'));
    }

    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'title' => 'required|max:100',
            'body' => 'required',
        ]);

        // データ保存
        Question::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => auth()->id(), // ログインユーザー
        ]);

        // 投稿後にリダイレクト
        return redirect()->route('questions.index')->with('success', '質問を投稿しました！');
    }
}

