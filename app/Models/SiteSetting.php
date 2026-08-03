<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enable_teacher_survey',
        'enable_student_survey',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enable_teacher_survey' => 'boolean',
        'enable_student_survey' => 'boolean',
    ];

    /**
     * Get the default settings or create if not exists.
     *
     * @return \App\Models\SiteSetting
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'enable_teacher_survey' => 1,
                'enable_student_survey' => 1,
            ]);
        }
        
        return $settings;
    }

    /**
     * Check if teacher surveys are enabled.
     *
     * @return bool
     */
    public static function isTeacherSurveyEnabled()
    {
        $settings = self::getSettings();
        return $settings->enable_teacher_survey;
    }

    /**
     * Check if student surveys are enabled.
     *
     * @return bool
     */
    public static function isStudentSurveyEnabled()
    {
        $settings = self::getSettings();
        return $settings->enable_student_survey;
    }

    /**
     * Toggle teacher survey status.
     *
     * @return bool
     */
    public static function toggleTeacherSurvey()
    {
        $settings = self::getSettings();
        $settings->enable_teacher_survey = !$settings->enable_teacher_survey;
        $settings->save();
        
        return $settings->enable_teacher_survey;
    }

    /**
     * Toggle student survey status.
     *
     * @return bool
     */
    public static function toggleStudentSurvey()
    {
        $settings = self::getSettings();
        $settings->enable_student_survey = !$settings->enable_student_survey;
        $settings->save();
        
        return $settings->enable_student_survey;
    }
}