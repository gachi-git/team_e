<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $_request)
    {
        $keyword = $_request->input('keyword');
        $filter  = $_request->input('filter', 'all');

        $query = Question::query()
            ->with(['user', 'tags', 'answers']);

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('body', 'like', "%{$keyword}%");
            });
        }

        if ($filter === 'unanswered') {
            $query->doesntHave('answers');
        } elseif ($filter === 'solved') {
            $query->whereNotNull('best_answer_id');
        }

        $questions = $query->latest()->paginate(10)->appends([
            'keyword' => $keyword,
            'filter'  => $filter,
        ]);

        return view('index', compact('questions', 'keyword', 'filter'));
    }

    public function create()
    {
        // Blade 側が @json($universityTags) を期待しているので名前を統一
        $universityTags = Tag::where('kind', 'university')
            ->orderBy('label')
            ->get(['id', 'label']);

        return view('create', compact('universityTags'));
    }

    /**
     * 大学タグは ID 配列のみ受け付け
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'  => ['required', 'string', 'max:255'],
            'body'   => ['required', 'string'],
            'university_tag_ids'   => ['nullable', 'array'],
            'university_tag_ids.*' => [
                'integer',
                Rule::exists('tags', 'id')->where('kind', 'university'),
            ],
        ]);

        // 重複IDを除去（hidden入力の二重追加などの保険）
        $tagIds = array_values(array_unique($validated['university_tag_ids'] ?? []));

        $payload = Arr::only($validated, ['title', 'body']);
        $payload['user_id'] = $request->user()->id;

        DB::transaction(function () use ($payload, $tagIds) {
            /** @var \App\Models\Question $question */
            $question = Question::create($payload);
            $question->tags()->sync($tagIds);
        });

        return redirect()
            ->route('questions.index')
            ->with('status', '質問を投稿しました。');
    }

    public function show(Request $request, $id)
    {
        $question = Question::with(['user', 'tags', 'answers.user'])->findOrFail($id);

        $viewedKey = 'viewed_question_' . $question->id;
        if (!$request->session()->has($viewedKey)) {
            $question->increment('views');
            $request->session()->put($viewedKey, true);
        }

        return view('questions.show', compact('question'));
    }

    public function storeAnswer(Request $request, $questionId)
    {
        $request->validate([
            'body' => ['required', 'string'],
        ]);

        Answer::create([
            'body'        => $request->body,
            'question_id' => $questionId,
            // 'user_id'   => $request->user()->id, // 認証運用に応じて有効化
        ]);

        return redirect()
            ->route('questions.show', $questionId)
            ->with('status', '回答を投稿しました。');
    }

    public function edit(Question $question)
    {
        $this->authorize('update', $question);
        return view('questions.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body'  => ['required', 'string'],
        ]);

        $question->update($request->only('title', 'body'));

        return redirect()
            ->route('questions.show', $question)
            ->with('success', '質問を更新しました');
    }

    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
        $question->delete();

        return redirect()
            ->route('questions.index')
            ->with('success', '質問を削除しました');
    }

    public function markBestAnswer(Request $request, $questionId, $answerId)
    {
        $question = Question::findOrFail($questionId);

        if ($request->user()->id !== $question->user_id) {
            abort(403, 'あなたはこの質問の投稿者ではありません。');
        }

        $question->best_answer_id = $answerId;
        $question->save();

        return redirect()
            ->route('questions.show', $questionId)
            ->with('status', 'ベストアンサーを設定しました。');
    }
}
