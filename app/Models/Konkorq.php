<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konkorq extends Model
{
    use HasFactory;

    protected $table = 'konkorqs';
    protected $fillable = [
        'user_id', 'question', 'answer1', 'answer2', 'answer3', 'answer4',
        'answer', 'checker', 'status', 'year', 'konkor_id', 'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function konkor()
    {
        return $this->belongsTo(Konkor::class, 'konkor_id');
    }
}