<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        // 通知を既読にする。
        $notification->markAsRead();

        // 通知データに保存してある質問IDを取得
        $questionId = $notification->data['question_id'] ?? null;

        //質問詳細へ遷移。
        if ($questionId) {
            return redirect()->route('questions.show', $questionId);
        }

        // 万一 question_id が無い場合
        return redirect()->route('questions.index');
    }
}
