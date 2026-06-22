<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Azmon extends Model
{
    use HasFactory;

    protected $table = 'azmons';
    protected $fillable = [
        'course_id', 'code', 'title', 'zarib', 'description', 'num', 'sath',
        'sessions', 'show_nomre', 'time', 'start', 'end', 'show_ans',
        'show_state', 'changeable', 'show_remain'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'azmon_id');
    }
}