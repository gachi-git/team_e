<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body'];

    // 質問に対する複数の回答
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
