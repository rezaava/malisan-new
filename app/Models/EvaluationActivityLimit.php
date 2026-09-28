<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationActivityLimit extends Model
{
    use HasFactory;

    protected $table = 'evaluation_activity_limits';

    protected $fillable = [
        'judging_quality',
        'self_test_quality',
        'report_quality',
        'question_quality',
        'self_test_participation',
        'judging_completion',
        'report_submission',
        'question_creation',
    ];

    protected $casts = [
        'judging_quality' => 'integer',
        'self_test_quality' => 'integer',
        'report_quality' => 'integer',
        'question_quality' => 'integer',
        'self_test_participation' => 'integer',
        'judging_completion' => 'integer',
        'report_submission' => 'integer',
        'question_creation' => 'integer',
    ];
}