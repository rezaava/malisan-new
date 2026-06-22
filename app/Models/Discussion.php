<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $table = 'discussions';
    protected $fillable = [
        'user_id', 'session_id', 'text', 'counter', 'status', 'comment1',
        'score2', 'score3', 'comment2', 'comment3', 'is_edit', 'score', 'level'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'sub_id')->where('type', 2);
    }
}