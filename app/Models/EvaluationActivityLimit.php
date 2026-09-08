<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationActivityLimit extends Model
{
    use HasFactory;

    protected $table = 'evaluation_activity_limits';

    protected $fillable = [
        'activity',
        'max_score',
    ];
}