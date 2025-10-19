<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = [
        'name',
        'name_kana',
        'type'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
