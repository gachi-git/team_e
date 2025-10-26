<?php

namespace App\Notifications;

use App\Models\Answer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAnswerNotification extends Notification
{
    use Queueable;

    protected $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }
//via()で通知チャンネルをdatabaseに指定。
    public function via($notifiable)
    {
        return ['database'];
    }
//toArray()で通知内容を定義。
//通知テーブルに「あなたの質問『〇〇』に新しい回答が投稿されました」というメッセージを保存。
    public function toArray($notifiable)
    {
        return [
            'message' => 'あなたの質問「' . $this->answer->question->title . '」に新しい回答が投稿されました。',
            'question_id' => $this->answer->question_id,
        ];
    }
}
