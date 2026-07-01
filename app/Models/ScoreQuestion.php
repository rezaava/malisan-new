<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreQuestion extends Model
{
    use HasFactory;

    protected $table = 'score_questions';

    protected $fillable = [
        'user_id', 'question_id',
        'score', 'negaresh', 'gozine', 'dark',
        'comment', 'status'
    ];

    // ثابت‌های وضعیت
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_RETURNED = 'returned';

    // ثابت‌های امتیاز
    const SCORE_EXCELLENT = 1;
    const SCORE_GOOD = 2;
    const SCORE_MEDIUM = 3;

    // روابط
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    // متدهای کمکی
    public function getScoreLabelAttribute()
    {
        $labels = [
            self::SCORE_EXCELLENT => 'عالی',
            self::SCORE_GOOD => 'خوب',
            self::SCORE_MEDIUM => 'متوسط',
        ];
        return $labels[$this->score] ?? 'نامشخص';
    }

    public function getScoreClassAttribute()
    {
        $classes = [
            self::SCORE_EXCELLENT => 'excellent',
            self::SCORE_GOOD => 'good',
            self::SCORE_MEDIUM => 'medium',
        ];
        return $classes[$this->score] ?? '';
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'در انتظار داوری',
            'approved' => 'تایید شده',
            'rejected' => 'رد شده',
            'returned' => 'برگشت داده شده',
        ];
        return $labels[$this->status] ?? 'نامشخص';
    }

    public function getStatusClassAttribute()
    {
        $classes = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'returned' => 'info',
        ];
        return $classes[$this->status] ?? 'secondary';
    }

    // چک کردن اینکه آیا ایراد دارد
    public function hasIssues()
    {
        return $this->negaresh == 1 || $this->gozine == 1 || $this->dark == 1;
    }

    // چک کردن اینکه آیا تایید شده
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isReturned()
    {
        return $this->status === self::STATUS_RETURNED;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    // دریافت مشکلات به صورت آرایه
    public function getIssuesAttribute()
    {
        $issues = [];
        if ($this->negaresh) $issues[] = 'ایراد نگارشی';
        if ($this->gozine) $issues[] = 'ایراد گزینه‌ها';
        if ($this->dark) $issues[] = 'ایراد گویایی';
        return $issues;
    }
}