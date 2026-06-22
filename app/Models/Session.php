<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'sessions';
    protected $fillable = [
        'course_id', 'text', 'file', 'link', 'majazi', 'aparat',
        'active', 'number', 'name'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'session_id');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'session_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'session_id');
    }
}