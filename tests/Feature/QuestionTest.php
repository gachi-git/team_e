<?php

use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Tag;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

// 質問投稿
it('can post a question', function () {
    $response = $this->post(route('questions.store'), [
        'title' => 'テスト質問タイトル',
        'body' => 'テスト質問本文',
    ]);

    $response->assertRedirect(); // リダイレクト
    $this->assertDatabaseHas('questions', [
        'title' => 'テスト質問タイトル',
        'body' => 'テスト質問本文',
        'user_id' => $this->user->id,
    ]);
});

// 質問作成フォームが表示される
it('can view question create page', function () {
    $response = $this->get(route('questions.create'));
    $response->assertStatus(200);
    $response->assertSee('タイトル'); // フォーム内の文字確認
});

// 質問一覧が表示される
it('can view question index page', function () {
    $question = Question::factory()->create();
    $response = $this->get(route('questions.index'));
    $response->assertStatus(200);
    $response->assertSee($question->title);
});

// 回答投稿
it('can post an answer', function () {
    $question = Question::factory()->create();
    $response = $this->post(route('answers.store', $question), [
        'body' => 'テスト回答本文',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('answers', [
        'body' => 'テスト回答本文',
        'question_id' => $question->id,
    ]);
});

// タグ付けと絞り込み
it('can attach tags to a question and filter by tag', function () {
    $tag = Tag::factory()->create(['key' => 'php']);
    $question = Question::factory()->create();
    $question->tags()->attach($tag);

    $response = $this->get(route('questions.index', ['tag' => $tag->key]));
    $response->assertStatus(200);
    $response->assertSee($question->title);
});

// 検索機能
it('can search questions by keyword in title or body', function () {
    $question = Question::factory()->create([
        'title' => '検索用タイトル',
        'body' => '検索用本文',
    ]);

    $response = $this->get(route('questions.index', ['keyword' => '検索用']));
    $response->assertStatus(200);
    $response->assertSee('検索用タイトル');
    $response->assertSee('検索用本文');
});
