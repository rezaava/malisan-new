<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdminSurveyController extends Controller
{
    public function angizesh_index()
    {
        $surveys = Survey::where('active', 1)->get();
        $settings = SiteSetting::getSettings();
        $totalSurveys = $surveys->count();
        $answeredSurveys = 0; // می‌تونید بر اساس نیاز محاسبه کنید
        
        return view('admin.survey', compact('surveys', 'settings', 'totalSurveys', 'answeredSurveys'));
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