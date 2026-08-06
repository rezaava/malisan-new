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
    public function isSkill()
    {
        return in_array($this->type, [1, 2]);
    }

    public function isLesson()
    {
        return $this->type == 1;
    }
    // روابط
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id');
    }

    public function teachers()
    {
        $roleId = Role::where('name', 'teacher')->value('id');
        
        if (!$roleId) {
            // اگر نقش teacher وجود نداشت، یک رابطه خالی برگردان
            return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                ->whereRaw('1 = 0');
        }
        
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->wherePivot('role_id', $roleId);
    }

    public function students()
    {
        $studentRole = Role::where('name', 'student')->first();
        
        if (!$studentRole) {
            return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                ->whereRaw('1 = 0');
        }
        
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
            ->wherePivot('role_id', $studentRole->id);
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
        return $this->hasOne(Setting::class, 'course_id');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'course_id');
    }
}