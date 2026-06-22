<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scoring extends Model
{
    use HasFactory;

    protected $table = 'scorings';
    protected $fillable = [
        'course_id', 'q_1', 'q_2', 'q_3', 'q_4', 'd_1', 'd_2', 'd_3', 'd_4',
        'e_1', 'e_2', 'e_3', 'e_4', 's_1', 's_2', 's_3', 's_4'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}