<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
    /**
     * صفحه اصلی مدیریت نظرسنجی‌ها
     * اگر دسته‌ای انتخاب شده باشد، نظرسنجی‌های آن دسته را نشان می‌دهد
     * در غیر این صورت، لیست دسته‌بندی‌ها را نمایش می‌دهد
     */
    public function index(Request $request)
    {
        // دریافت تنظیمات سایت
        $settings = SiteSetting::getSettings();

        // دسته‌بندی انتخاب شده
        $categoryId = $request->input('category');

        if ($categoryId) {
            // نمایش نظرسنجی‌های یک دسته خاص
            $surveys = Survey::with('options')
                ->where('cat_id', $categoryId)
                ->orderBy('created_at', 'desc')
                ->get();

            $selectedCategory = Category::find($categoryId);
            $categories = Category::withCount('surveys')->get(); // برای منوی بازگشت

            // آمار مخصوص این دسته
            $totalSurveys = $surveys->count();
            $answeredSurveys = OptionUser::whereIn('survey_id', $surveys->pluck('id'))
                ->distinct('survey_id')
                ->count();

            return view('admin.survey', compact(
                'surveys', 'settings', 'categories', 'selectedCategory',
                'totalSurveys', 'answeredSurveys', 'categoryId'
            ));
        } else {
            // نمایش لیست دسته‌بندی‌ها
            $categories = Category::withCount('surveys')->get();
            $surveys = collect(); // خالی

            // آمار کلی (همه نظرسنجی‌ها)
            $totalSurveys = Survey::count();
            $answeredSurveys = OptionUser::distinct('survey_id')->count();

            return view('admin.survey', compact(
                'categories', 'surveys', 'settings',
                'totalSurveys', 'answeredSurveys', 'categoryId'
            ));
        }
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