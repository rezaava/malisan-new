<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions;
    protected $table = 'users';
    
    protected $fillable = [
        'role', 'name', 'family', 'gender', 'email', 'national', 'shenasname',
        'personal', 'birthdate', 'city_birth', 'city', 'address', 'postal',
        'tel', 'mobile', 'sms', 'active', 'tour', 'tel_work', 'uni_email',
        'web', 'scholar', 'social', 'degree', 'field', 'trend', 'trend_en',
        'research', 'image', 'shaba', 'turn', 'password', 'aneto_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // متدهای کمکی برای بررسی نقش‌ها
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isTeacher()
    {
        return $this->hasRole('teacher');
    }

    public function isStudent()
    {
        return $this->hasRole('student');
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->family;
    }

    // روابط
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id');
    }

    public function amalis()
    {
        return $this->hasMany(Amali::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'user_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'student_id');
    }

    public function coworkers()
    {
        return $this->hasMany(Coworker::class, 'user_id');
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'user_id');
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'user_id');
    }

    public function exerciseAnswers()
    {
        return $this->hasMany(ExerciseAnswer::class, 'user_id');
    }

    public function fcms()
    {
        return $this->hasMany(Fcm::class, 'user_id');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'user_id');
    }

    public function konkorqs()
    {
        return $this->hasMany(Konkorq::class, 'user_id');
    }

    public function leitners()
    {
        return $this->hasMany(Leitner::class, 'user_id');
    }

    public function optionUsers()
    {
        return $this->hasMany(OptionUser::class, 'user_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'user_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'user_id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'user_id');
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'user_id');
    }

    public function studentAdjectives()
    {
        return $this->hasMany(StudentAdjective::class, 'student_id');
    }

    public function studentAdjectivesTeacher()
    {
        return $this->hasMany(StudentAdjective::class, 'teacher_id');
    }

    public function studentEvents()
    {
        return $this->hasMany(StudentEvent::class, 'student_id');
    }

    public function studentEventsTeacher()
    {
        return $this->hasMany(StudentEvent::class, 'teacher_id');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'user_id');
    }

    public function touradmins()
    {
        return $this->hasMany(Touradmin::class, 'user_id');
    }

    public function tourusers()
    {
        return $this->hasMany(Touruser::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}