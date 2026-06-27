<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Option;
use App\Models\OptionUser;
use App\Models\Survey;
use Auth;
use Illuminate\Http\Request;
use Validator;

class SurveyController extends Controller
{
        /**
     * نمایش لیست نظرسنجی‌ها
     */
    public function index($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();
        $courseId = $id;

        // دریافت نظرسنجی‌های این درس
        $surveys = Survey::where('cat_id', $courseId)->orderBy('id', 'asc')->get();

        // تکمیل اطلاعات هر نظرسنجی
        foreach ($surveys as $survey) {
            $survey->options = Option::where('survey_id', $survey->id)->get();

            // دریافت کنندگان
            if ($survey->group == '0') {
                $survey->recipient = 'همه دانشجویان';
            } elseif ($survey->group < 0) {
                $survey->recipient = 'دانشجویان استاد';
            } else {
                $courseObj = Course::find($survey->group);
                $survey->recipient = $courseObj ? $courseObj->name : 'نامشخص';
            }

            // نوع نظرسنجی
            $survey->type_text = match ($survey->type) {
                1 => 'پاسخ کوتاه',
                2 => 'تک جواب',
                3 => 'چند جواب',
                default => 'نامشخص',
            };

            // وضعیت
            $survey->status_text = $survey->active == 1 ? 'فعال' : 'غیر فعال';
        }

        $courses = $user->courses()->get();
        $categories = Category::all();

        return view('teacher.surveys', compact(
            'surveys',
            'courses',
            'categories',
            'course',
            'courseId'
        ));
    }
    /**
     * ایجاد نظرسنجی جدید
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:3',
            'cat_id' => 'required|exists:courses,id',
            'answer' => 'required|in:1,2,3',
            'options' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $survey = Survey::create([
                'text' => $request->question,
                'cat_id' => $request->cat_id,
                'type' => $request->answer,
                'user_id' => Auth::id(),
                'active' => 1,
                'group' => 0,
            ]);

            // ذخیره گزینه‌ها (برای نوع ۲ و ۳)
            if (in_array($request->answer, [2, 3]) && $request->options) {
                $options = explode("\n", trim($request->options));
                foreach ($options as $option) {
                    if (trim($option)) {
                        Option::create([
                            'survey_id' => $survey->id,
                            'option' => trim($option),
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'نظرسنجی با موفقیت ایجاد شد');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در ایجاد نظرسنجی: ' . $e->getMessage());
        }
    }

    /**
     * دریافت نتایج نظرسنجی (AJAX)
     */
    public function results($id)
    {
        $survey = Survey::findOrFail($id);
        $options = Option::where('survey_id', $survey->id)->get();

        if ($survey->type == 1) {
            // پاسخ کوتاه
            $answers = OptionUser::where('survey_id', $survey->id)->pluck('answer');
            return response()->json([
                'type' => 'descriptive',
                'survey' => $survey->text,
                'answers' => $answers,
            ]);
        }

        // چند گزینه‌ای
        $total = OptionUser::where('survey_id', $survey->id)->count();
        $results = [];

        foreach ($options as $option) {
            $count = OptionUser::where('survey_id', $survey->id)
                ->where(function ($q) use ($option) {
                    $q->where('answer', $option->id)
                        ->orWhere('answer', $option->option);
                })
                ->count();

            $results[] = [
                'label' => $option->option,
                'count' => $count,
                'percent' => $total > 0 ? round(($count / $total) * 100, 1) : 0,
            ];
        }

        return response()->json([
            'type' => 'choice',
            'survey' => $survey->text,
            'total' => $total,
            'results' => $results,
        ]);
    }

    /**
     * حذف نظرسنجی
     */
    public function destroy($id)
    {
        try {
            $survey = Survey::findOrFail($id);
            
            // حذف گزینه‌ها و پاسخ‌ها
            Option::where('survey_id', $id)->delete();
            OptionUser::where('survey_id', $id)->delete();
            $survey->delete();

            return redirect()->back()->with('success', 'نظرسنجی با موفقیت حذف شد');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در حذف نظرسنجی: ' . $e->getMessage());
        }
    }

    /**
     * تغییر وضعیت فعال/غیرفعال
     */
    public function toggleActive($id)
    {
        try {
            $survey = Survey::findOrFail($id);
            $survey->active = $survey->active == 1 ? 0 : 1;
            $survey->save();

            $status = $survey->active == 1 ? 'فعال' : 'غیرفعال';
            return redirect()->back()->with('success', "وضعیت نظرسنجی به {$status} تغییر یافت");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در تغییر وضعیت: ' . $e->getMessage());
        }
    }

    /**
     * نمایش پرسش اولیه (سوالاتی که کاربر پاسخ نداده)
     */
    public function onboardingSurvey()
    {
        $user = Auth::user();

        $answeredSurveyIds = OptionUser::where('user_id', $user->id)
            ->pluck('survey_id')
            ->toArray();

        $survey = Survey::where('active', 1)
            ->whereNotIn('id', $answeredSurveyIds)
            ->where('type',1)
            ->inRandomOrder()
            ->first();

        if (!$survey) {
            session()->put('onboarding_done', true);
            return redirect()->route('index_student')
                ->with('success', 'شما به تمام پرسش‌ها پاسخ داده‌اید!');
        }

        // دریافت گزینه‌های سوال
        $options = Option::where('survey_id', $survey->id)->get();

        return view('student.onboarding', compact('survey', 'options'))->with([
            'pageTitle' => 'پرسش اولیه',
            'pageName' => 'پرسش اولیه',
            'pageDescription' => 'لطفاً به این سوال پاسخ دهید',
        ]);
    }

    /**
     * ذخیره پاسخ پرسش اولیه (پشتیبانی از انواع مختلف)
     */
    public function submitOnboarding(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'survey_id' => 'required|exists:surveys,id',
            'answer' => 'required_if:survey_type,1,2|nullable|string|max:500',
            'answers' => 'required_if:survey_type,3|nullable|array',
            'answers.*' => 'string|max:500',
            'survey_type' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $surveyType = $request->survey_type;
            $surveyId = $request->survey_id;

            // نوع 1: پاسخ کوتاه (نظر کاربر)
            if ($surveyType == 1) {
                OptionUser::create([
                    'survey_id' => $surveyId,
                    'user_id' => $user->id,
                    'answer' => $request->answer,
                ]);
            }

            // نوع 2: تک جواب (یک گزینه)
            elseif ($surveyType == 2) {
                OptionUser::create([
                    'survey_id' => $surveyId,
                    'user_id' => $user->id,
                    'answer' => $request->answer,
                ]);
            }

            // نوع 3: چند جواب (چند گزینه)

            elseif ($surveyType == 3) {
                $answers = $request->answers ?? [];
                foreach ($answers as $answer) {
                    if (!empty($answer)) {
                        OptionUser::create([
                            'survey_id' => $surveyId,
                            'user_id' => $user->id,
                            'answer' => $answer,
                        ]);
                    }
                }
            }

            return redirect()->route('student.onboarding')
                ->with('success', 'پاسخ شما با موفقیت ثبت شد!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطا در ثبت پاسخ: ' . $e->getMessage());
        }
    }

    /**
     * رد کردن پرسش
     */
    public function skipOnboarding()
    {
        session()->put('onboarding_done', true);

        return redirect()->route('index_student')
            ->with('info', 'شما می‌توانید بعداً در بخش نظرسنجی‌ها به این سوال پاسخ دهید.');
    }
}
