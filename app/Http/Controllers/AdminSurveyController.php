<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Survey;
use App\Models\SiteSetting;
use App\Models\OptionUser;
use Illuminate\Http\Request;

class AdminSurveyController extends Controller
{
    /**
     * صفحه اصلی مدیریت نظرسنجی‌ها
     */
    public function index()
    {
        $settings = SiteSetting::getSettings();

        $categories = Category::withCount('surveys')
            ->orderBy('name')
            ->get();

        $totalSurveys = Survey::count();

        $answeredSurveys = OptionUser::distinct('survey_id')
            ->count('survey_id');

        return view(
            'admin.survey.categories',
            compact(
                'categories',
                'settings',
                'totalSurveys',
                'answeredSurveys'
            )
        );
    }


    /**
     * افزودن دسته‌بندی
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'نام دسته‌بندی الزامی است.',
            'name.string' => 'نام دسته‌بندی باید متنی باشد.',
            'name.max' => 'نام دسته‌بندی نمی‌تواند بیشتر از 255 کاراکتر باشد.',
        ]);

        try {

            $category = new Category();

            $category->name = $request->name;

            $category->save();

            return response()->json([
                'success' => true,

                'message' => 'دسته‌بندی با موفقیت اضافه شد.',

                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'surveys_count' => 0,
                ],
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,

                'message' =>
                    'خطا در ایجاد دسته‌بندی: ' .
                    $e->getMessage(),
            ], 500);
        }
    }


    /**
     * صفحه سوالات یک دسته
     */
    public function category($id)
    {
        $settings = SiteSetting::getSettings();

        $category = Category::findOrFail($id);

        $surveys = Survey::with('options')
            ->where('cat_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $categories = Category::withCount('surveys')
            ->orderBy('name')
            ->get();

        $totalSurveys = $surveys->count();

        $answeredSurveys = OptionUser::whereIn(
            'survey_id',
            $surveys->pluck('id')
        )
            ->distinct('survey_id')
            ->count('survey_id');

        return view(
            'admin.survey.questions',
            compact(
                'category',
                'surveys',
                'categories',
                'settings',
                'totalSurveys',
                'answeredSurveys'
            )
        );
    }


    /**
     * دریافت جزئیات یک نظرسنجی
     */
    public function show($id)
    {
        $survey = Survey::with([
            'options.optionUsers'
        ])->findOrFail($id);

        $totalVotes =
            $survey->optionUsers->count();

        $optionsData =
            $survey->options->map(
                function ($option) use ($totalVotes) {

                    $count =
                        $option->optionUsers->count();

                    $percentage =
                        $totalVotes > 0
                            ? round(
                                ($count / $totalVotes) * 100,
                                1
                            )
                            : 0;

                    return [
                        'id' =>
                            $option->id,

                        'text' =>
                            $option->text,

                        'count' =>
                            $count,

                        'percentage' =>
                            $percentage,
                    ];
                }
            );

        return response()->json([

            'success' => true,

            'survey' => $survey,

            'options' => $optionsData,

            'total_votes' => $totalVotes,

        ]);
    }


    /**
     * تغییر وضعیت نظرسنجی دانشجو
     */
    public function toggleStudentSurvey(Request $request)
    {
        try {

            $settings =
                SiteSetting::getSettings();

            $settings->enable_student_survey =
                !$settings->enable_student_survey;

            $settings->save();

            return response()->json([

                'success' => true,

                'status' =>
                    $settings->enable_student_survey,

                'message' =>
                    $settings->enable_student_survey
                        ? 'نظرسنجی دانشجو فعال شد'
                        : 'نظرسنجی دانشجو غیرفعال شد',

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'خطا در تغییر وضعیت: ' .
                    $e->getMessage(),

            ], 500);
        }
    }


    /**
     * تغییر وضعیت نظرسنجی استاد
     */
    public function toggleTeacherSurvey(Request $request)
    {
        try {

            $settings =
                SiteSetting::getSettings();

            $settings->enable_teacher_survey =
                !$settings->enable_teacher_survey;

            $settings->save();

            return response()->json([

                'success' => true,

                'status' =>
                    $settings->enable_teacher_survey,

                'message' =>
                    $settings->enable_teacher_survey
                        ? 'نظرسنجی استاد فعال شد'
                        : 'نظرسنجی استاد غیرفعال شد',

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'خطا در تغییر وضعیت: ' .
                    $e->getMessage(),

            ], 500);
        }
    }


    /**
     * دریافت وضعیت فعلی تنظیمات
     */
    public function getSettings()
    {
        try {

            $settings =
                SiteSetting::getSettings();

            return response()->json([

                'success' => true,

                'data' => $settings,

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'خطا در دریافت تنظیمات',

            ], 500);
        }
    }


    /**
     * ویرایش دسته‌بندی
     */
    public function updateCategory(
        Request $request,
        $id
    ) {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' =>
                'نام دسته‌بندی الزامی است.',

            'name.string' =>
                'نام دسته‌بندی باید متنی باشد.',

            'name.max' =>
                'نام دسته‌بندی نمی‌تواند بیشتر از 255 کاراکتر باشد.',
        ]);

        try {

            $category =
                Category::findOrFail($id);

            $category->name =
                $request->name;

            $category->save();

            return response()->json([

                'success' => true,

                'message' =>
                    'دسته‌بندی با موفقیت ویرایش شد.',

                'category' => [

                    'id' =>
                        $category->id,

                    'name' =>
                        $category->name,

                    'surveys_count' =>
                        $category
                            ->surveys()
                            ->count(),

                ],

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'خطا در ویرایش دسته‌بندی: ' .
                    $e->getMessage(),

            ], 500);
        }
    }


    /**
     * حذف دسته‌بندی
     */
    public function deleteCategory($id)
    {
        try {

            $category =
                Category::findOrFail($id);

            $surveysCount =
                $category
                    ->surveys()
                    ->count();

            if ($surveysCount > 0) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        "این دسته‌بندی دارای {$surveysCount} سوال است. ابتدا سوالات را حذف کنید.",

                ], 400);
            }


            $category->delete();


            return response()->json([

                'success' => true,

                'message' =>
                    'دسته‌بندی با موفقیت حذف شد.',

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'خطا در حذف دسته‌بندی: ' .
                    $e->getMessage(),

            ], 500);
        }
    }


    /**
     * =====================================================
     * غیرفعال کردن تمام سوالات یک دسته‌بندی
     * =====================================================
     */
    public function deactivateCategorySurveys($id)
    {
        try {

            // پیدا کردن دسته
            $category =
                Category::findOrFail($id);


            // فقط سوالات همین دسته
            // که در حال حاضر active هستند
            // به active = 0 تغییر می‌کنند

            $updatedCount =
                Survey::where(
                    'cat_id',
                    $category->id
                )
                ->where(
                    'active',
                    1
                )
                ->update([
                    'active' => 0
                ]);


            if ($updatedCount > 0) {

                $message =
                    "{$updatedCount} سوال این دسته با موفقیت غیرفعال شد.";

            } else {

                $message =
                    'تمام سوالات این دسته از قبل غیرفعال بودند.';
            }


            return response()->json([

                'success' => true,

                'message' => $message,

                'updated_count' => $updatedCount,

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'خطا در غیرفعال کردن سوالات: ' .
                    $e->getMessage(),

            ], 500);
        }
    }
}
