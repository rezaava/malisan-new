<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';
    protected $fillable = [
        'name', 'archieve', 'header', 'code', 'private', 'period', 'type',
        'desc', 'price', 'length', 'sessions_length', 'majazi', 'max_session',
        'num_q', 'score_e', 'score_d', 'score_q', 'status', 'active', 'quiz',
        'davari', 'faaliat', 'pishraft'
    ];

    // روابط
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id');
    }

    public function amalis()
    {
        return $this->hasMany(Amali::class, 'course_id');
    }

    public function azmons()
    {
        return $this->hasMany(Azmon::class, 'course_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'course_id');
    }

    public function scorings()
    {
        return $this->hasMany(Scoring::class, 'course_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'course_id');
    }

    public function settings()
    {
        return $this->hasMany(Setting::class, 'course_id');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'course_id');
    }
}