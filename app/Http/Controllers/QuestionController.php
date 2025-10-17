<?php
namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;


class QuestionController extends Controller
{
    // 一覧表示
    public function index()
    {
        $questions = Question::latest()->paginate(10);
        return view('index', compact('questions'));
    }

    // 作成フォーム表示
    public function create()
    {
        return view('create');
    }

    // 保存して一覧へ戻す
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','max:255'],
            'body'  => ['required'],
        ]);

        Question::create($data);

        return redirect()->route('questions.index')->with('status', '質問を投稿しました。');
    }
}