<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'course_id', 'jalasat', 'tarahi_soal_nomre', 'tarahi_soal_desc',
        'ersal_gozaresh_nomre', 'ersal_gozaresh_desc', 'taklif_seminar_nomre',
        'taklif_seminar_desc', 'taklif_seminar_type', 'quiz_mid_nomre',
        'quiz_mid_desc', 'quiz_mid_type', 'pishraft_nomre', 'pishraft_desc',
        'talash_nomre', 'talash_desc', 'hozor_nomre', 'hozor_desc',
        'amali_nomre', 'amali_desc', 'final_nomre', 'final_desc',
        'erfagh_nomre', 'erfagh_desc', 'soal_last', 'gozaresh_last',
        'mostamar_nomre', 'taklif_last', 'max_soal', 'min_soal',
        'min_davari', 'max_taklif', 'max_seminar', 'max_gozaresh',
        'max_gheibat', 'min_w_khod', 'q_num', 'sath_khod', 'show_khod',
        'quiz_num', 'sath_quiz', 'natije', 'show_quiz', 'azmon_nomre'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}