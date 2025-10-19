<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    // user_id を追加
    protected $fillable = ['body', 'question_id', 'user_id'];

    /**
     * 回答が属する質問
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * 回答者（ユーザー）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
