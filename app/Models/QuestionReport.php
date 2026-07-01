<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionReport extends Model
{
    use HasFactory;

    protected $table = 'question_reports';

    protected $fillable = [
        'user_id', 'question_id', 'description',
        'status', 'admin_response', 'reviewed_by', 'reviewed_at'
    ];

    // وضعیت‌ها
    const STATUS_PENDING = 'pending';
    const STATUS_REVIEWED = 'reviewed';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_REJECTED = 'rejected';

    // روابط
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // متدهای کمکی
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'در انتظار بررسی',
            'reviewed' => 'بررسی شده',
            'resolved' => 'رفع شده',
            'rejected' => 'رد شده',
        ];
        return $labels[$this->status] ?? 'نامشخص';
    }

    public function getStatusClassAttribute()
    {
        $classes = [
            'pending' => 'warning',
            'reviewed' => 'info',
            'resolved' => 'success',
            'rejected' => 'danger',
        ];
        return $classes[$this->status] ?? 'secondary';
    }
}