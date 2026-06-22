<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leitner extends Model
{
    use HasFactory;

    protected $table = 'leitners';
    protected $fillable = ['user_id', 'question_id', 'konkor_id', 'box'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function konkor()
    {
        return $this->belongsTo(Konkor::class, 'konkor_id');
    }
}