<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Score extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'scores';
    protected $fillable = [
        'user_id', 'sub_id', 'type', 
        'score1', 'score2', 'score3',
        'comment1', 'comment2', 'comment3',
        'description'
    ];

    protected $dates = ['deleted_at'];

    // نوع‌های محتوا
    const TYPE_QUESTION = 1;
    const TYPE_DISCUSSION = 2;
    const TYPE_EXERCISE = 3;

    // امتیازها
    const SCORE_EXCELLENT = 1;
    const SCORE_GOOD = 2;
    const SCORE_MEDIUM = 3;
    const SCORE_WEAK = 4;
    const SCORE_VERY_WEAK = 5;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'sub_id');
    }

    public function discussion()
    {
        return $this->belongsTo(Discussion::class, 'sub_id');
    }

    // متدهای کمکی
    public function getScoreLabelAttribute()
    {
        $labels = [
            self::SCORE_EXCELLENT => 'عالی',
            self::SCORE_GOOD => 'خوب',
            self::SCORE_MEDIUM => 'متوسط',
            self::SCORE_WEAK => 'ضعیف',
            self::SCORE_VERY_WEAK => 'خیلی ضعیف',
        ];
        return $labels[$this->score1] ?? 'نامشخص';
    }

    public function getScoreClassAttribute()
    {
        $classes = [
            self::SCORE_EXCELLENT => 'excellent',
            self::SCORE_GOOD => 'good',
            self::SCORE_MEDIUM => 'medium',
            self::SCORE_WEAK => 'weak',
            self::SCORE_VERY_WEAK => 'very-weak',
        ];
        return $classes[$this->score1] ?? '';
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_QUESTION => 'سوال',
            self::TYPE_DISCUSSION => 'گزارش',
            self::TYPE_EXERCISE => 'تکلیف',
        ];
        return $labels[$this->type] ?? 'نامشخص';
    }

    public function getTypeIconAttribute()
    {
        $icons = [
            self::TYPE_QUESTION => 'fa-question-circle',
            self::TYPE_DISCUSSION => 'fa-file-alt',
            self::TYPE_EXERCISE => 'fa-tasks',
        ];
        return $icons[$this->type] ?? 'fa-circle';
    }
}