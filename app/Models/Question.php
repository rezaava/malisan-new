<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $fillable = [
        'question', 'answer1', 'answer2', 'answer3', 'answer4', 'answer',
        'user_id', 'session_id', 'status', 'star', 'counter', 'is_edit',
        'score', 'level', 'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function leitners()
    {
        return $this->hasMany(Leitner::class, 'question_id');
    }
}