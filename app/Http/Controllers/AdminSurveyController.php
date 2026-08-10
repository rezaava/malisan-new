<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SiteSetting;
use App\Models\Option;
use App\Models\OptionUser;
use Illuminate\Http\Request;

class AdminSurveyController extends Controller
{
    /**
     * صفحه اصلی مدیریت نظرسنجی‌ها
     */
    public function angizesh_index()
    {
        // دریافت تمام نظرسنجی‌ها (به‌صورت صفحه‌بندی شده)
        $surveys = Survey::with('options')->orderBy('created_at', 'desc')->get();
        
        // تنظیمات سایت
        $settings = SiteSetting::getSettings();
        
        return view('admin.survey', compact('surveys', 'settings'));
    }

    /**
     * دریافت جزئیات یک نظرسنجی برای نمایش در مودال (AJAX)
     */
    public function show($id)
    {
        $survey = Survey::with(['options.optionUsers'])->findOrFail($id);
        
        $totalVotes = $survey->optionUsers->count();
        
        $optionsData = $survey->options->map(function($option) use ($totalVotes) {
            $count = $option->optionUsers->count();
            $percentage = $totalVotes > 0 ? round(($count / $totalVotes) * 100, 1) : 0;
            return [
                'id' => $option->id,
                'text' => $option->text,
                'count' => $count,
                'percentage' => $percentage,
            ];
        });
        
        return response()->json([
            'success' => true,
            'survey' => $survey,
            'options' => $optionsData,
            'total_votes' => $totalVotes,
        ]);
    }

    /**
     * تغییر وضعیت نظرسنجی دانشجو (AJAX)
     */
    public function toggleStudentSurvey(Request $request)
    {
        try {
            $settings = SiteSetting::getSettings();
            $settings->enable_student_survey = !$settings->enable_student_survey;
            $settings->save();
            
            return response()->json([
                'success' => true,
                'status' => $settings->enable_student_survey,
                'message' => $settings->enable_student_survey ? 'نظرسنجی دانشجو فعال شد' : 'نظرسنجی دانشجو غیرفعال شد'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در تغییر وضعیت: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تغییر وضعیت نظرسنجی استاد (AJAX)
     */
    public function toggleTeacherSurvey(Request $request)
    {
        try {
            $settings = SiteSetting::getSettings();
            $settings->enable_teacher_survey = !$settings->enable_teacher_survey;
            $settings->save();
            
            return response()->json([
                'success' => true,
                'status' => $settings->enable_teacher_survey,
                'message' => $settings->enable_teacher_survey ? 'نظرسنجی استاد فعال شد' : 'نظرسنجی استاد غیرفعال شد'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در تغییر وضعیت: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * دریافت وضعیت فعلی تنظیمات (AJAX)
     */
    public function getSettings()
    {
        try {
            $settings = SiteSetting::getSettings();
            return response()->json([
                'success' => true,
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطا در دریافت تنظیمات'
            ], 500);
        }
    }
}