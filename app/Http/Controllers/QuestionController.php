<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuestionController extends Controller
{

    use AuthorizesRequests;

    public function index(Request $_request)
    {
        $keyword = $_request->input('keyword');
        $filter  = $_request->input('filter', 'all'); // ← チェックボックスの状態（デフォルト: all）

        $query = Question::query()->with('user', 'tags', 'answers'); // N+1回避

        // 🔍 キーワード検索
        if (! empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('body', 'like', "%{$keyword}%");
            });
        }

        // ✅ フィルタリング処理
        if ($filter === 'unanswered') {
            // 回答なし
            $query->doesntHave('answers');
        } elseif ($filter === 'solved') {
            // ベストアンサーあり
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
        return view('create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => ['required', 'max:255'],
            'body'     => ['required'],
            'hashtags' => ['nullable', 'string'],
        ]);

        // 投稿者
        $data['user_id'] = $request->user()->id;

        // 質問作成
        $question = Question::create($data);

        // ハッシュタグ抽出 → 保存
        $labels = $this->extractHashtags($data['hashtags'] ?? '');
        if (!empty($labels)) {
            $tagIds = [];
            foreach ($labels as $label) {
                $key = $this->makeTagKey($label);      // 日本語対応のキー生成
                if ($key === '') continue;
                $tag = Tag::firstOrCreate(
                    ['key' => $key, 'kind' => 'free'], // (key,kind)で一意
                    ['label' => $label]
                );
                $tagIds[] = $tag->id;
            }
            if (!empty($tagIds)) {
                $question->tags()->sync($tagIds);
            }
        }

        return redirect()->route('questions.index')->with('status', '質問を投稿しました。');
    }

    // 連結(#九州大学#理学部#登山部)／スペース／カンマ区切りすべて対応
    private function extractHashtags(string $input): array
    {
        $labels = [];

        $s = trim((string)$input);
        if ($s === '') return [];

        // 全角＃→半角# に統一
        $s = str_replace('＃', '#', $s);

        if (mb_strpos($s, '#') !== false) {
            // # が含まれる場合は正規表現のみで抽出（連結・混在OK、二重取り防止）
            if (preg_match_all('/#([^\s#]+)/u', $s, $m)) {
                $labels = $m[1];
            }
        } else {
            // # が無い場合は空白／カンマ／読点で分割
            $parts = preg_split('/[\s,、，　]+/u', $s, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($parts ?: [] as $t) {
                $t = trim($t);
                if ($t !== '') $labels[] = $t;
            }
        }

        // 後処理：トリム・空除去・長さ制限・重複除去
        $labels = array_values(array_unique(array_filter(array_map(function ($x) {
            $x = trim($x);
            if ($x === '') return '';
            return mb_substr($x, 0, 30); // 30文字上限
        }, $labels), fn($x) => $x !== '')));

        return $labels;
    }

    // 日本語でも必ず非空になるキー生成（空白/読点を除去、正規化、lower化、空ならmd5）
    private function makeTagKey(string $label): string
    {
        $s = trim((string)$label);
        if ($s === '') return '';

        // 全角・半角/カナの揺れを正規化
        $s = mb_convert_kana($s, 'asKV', 'UTF-8'); // a:全角英数→半角, s:スペース, K:半角ｶﾅ→全角, V:濁点結合
        // スペース/読点/カンマなどを除去
        $s = preg_replace('/[\s　,、，．。]+/u', '', $s) ?? '';
        // 小文字化
        $s = mb_strtolower($s, 'UTF-8');

        // ここまでで空になったらフォールバック（純日本語のみ等での保険）
        if ($s === '') {
            $s = md5($label);
        }
        return $s;
    }

    public function show($id)
    {
        $question = Question::with('user', 'tags', 'answers.user')->findOrFail($id);
        return view('questions.show', compact('question'));
    }

    // ★ここだけ残す（重複禁止）
    public function storeAnswer(Request $request, $questionId)
    {
        $request->validate([
            'body' => 'required',
        ]);

        Answer::create([
            'body'        => $request->body,
            'question_id' => $questionId,
            // 投稿者を紐づけたい場合は Answer モデル側の $fillable に user_id を用意して下の行を使う
            // 'user_id'     => $request->user()->id,
        ]);

        return redirect()->route('questions.show', $questionId)
            ->with('status', '回答を投稿しました。');
    }

    // 編集画面表示
    public function edit(Question $question)
    {
        $this->authorize('update', $question); // ポリシーで本人確認
        return view('questions.edit', compact('question'));
    }

    // 更新処理
    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $question->update($request->only('title', 'body'));

        return redirect()->route('questions.show', $question)->with('success', '質問を更新しました');
    }

    // 削除処理
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);

        $question->delete();

        return redirect()->route('questions.index')->with('success', '質問を削除しました');
    }


    /**
     * ベストアンサーに設定
     */
    public function markBestAnswer(Request $request, $questionId, $answerId)
    {
        $question = Question::findOrFail($questionId);

        // 質問者本人のみ許可
        if ($request->user()->id !== $question->user_id) {
            abort(403, 'あなたはこの質問の投稿者ではありません。');
        }

        // ベストアンサーを設定
        $question->best_answer_id = $answerId;
        $question->save();

        return redirect()
            ->route('questions.show', $questionId)
            ->with('status', 'ベストアンサーを設定しました。');
    }
}
