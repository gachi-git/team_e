<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Notifications\NewAnswerNotification;

class QuestionFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 質問をハッシュタグ付きで投稿できる
     */
    public function test_it_can_post_a_question_with_hashtags()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('questions.store'), [
            'title'    => 'タグ付き質問',
            'body'     => 'LaravelとPHPについて教えてください。',
            'hashtags' => '#Laravel #PHP',
        ]);

        $response->assertRedirect(route('questions.index'));

        $question = Question::where('title', 'タグ付き質問')->first();
        $this->assertNotNull($question);

        // タグが保存されていること
        $this->assertDatabaseHas('tags', ['key' => 'laravel']);
        $this->assertDatabaseHas('tags', ['key' => 'php']);

        // 質問とタグの関係
        $this->assertTrue($question->tags()->pluck('key')->contains('laravel'));
        $this->assertTrue($question->tags()->pluck('key')->contains('php'));
    }

    /**
     * 回答を投稿すると通知が送られる
     */
    public function test_it_sends_notification_to_question_owner_when_answer_is_posted()
    {
        Notification::fake();

        $questionOwner = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $questionOwner->id]);

        $answerUser = User::factory()->create();

        $response = $this->actingAs($answerUser)->post(
            route('answers.store', $question->id),
            ['body' => 'これはテスト回答です。']
        );

        $response->assertRedirect();

        // 通知が送られたか確認
        Notification::assertSentTo(
            [$questionOwner],
            NewAnswerNotification::class
        );
    }

    /**
     * 回答がベストアンサーに設定できる
     */
    public function test_it_can_mark_an_answer_as_best_answer()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);
        $answer = Answer::factory()->create([
            'question_id' => $question->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post(
            route('answers.best', [$question->id, $answer->id])
        );

        $response->assertRedirect();

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'best_answer_id' => $answer->id,
        ]);
    }

    /**
     * タグで質問を絞り込める
     */
    public function test_it_can_filter_questions_by_tag()
    {
        $tag = Tag::factory()->create(['key' => 'php', 'label' => 'PHP']);
        $question1 = Question::factory()->create();
        $question2 = Question::factory()->create();

        $question1->tags()->attach($tag->id);
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('questions.index', ['tag' => 'php']));

        $response->assertStatus(200);
        $response->assertSee($question1->title);
        $response->assertDontSee($question2->title);
    }
}
