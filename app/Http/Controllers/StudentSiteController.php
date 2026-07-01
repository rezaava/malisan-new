<?php

namespace App\Http\Controllers;

use App\Models\Amali;
use App\Models\Angizesh;
use App\Models\Azmon;
use App\Models\Course;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\ExerciseAnswer;
use App\Models\Konkor;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Role;
use App\Models\Score;
use App\Models\ScoreDiscussion;
use App\Models\ScoreExercise;
use App\Models\ScoreQuestion;
use App\Models\Scoring;
use App\Models\Session;
use App\Models\Setting;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class StudentSiteController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('student') && !session()->has('onboarding_done')) {
            return redirect()->route('student.onboarding');
        }
        
        // دریافت پیام انگیزشی
        $message = Angizesh::whereIn('level', [7, 8])
            ->inRandomOrder()
            ->first();
        
        // دریافت دوره‌های کاربر
        $userCourseIds = DB::table('course_user')
            ->where('user_id', $user->id)
            ->pluck('course_id');
        
        // دریافت آزمون‌های شرکت کرده
        $participatedAzmonIds = DB::table('quizzes')
            ->where('user_id', $user->id)
            ->whereNotNull('azmon_id')
            ->pluck('azmon_id');
        
        // دریافت آزمون‌های فعال
        $activeExams = Azmon::whereIn('course_id', $userCourseIds)
        ->where('start', '<=', Carbon::now())
        ->where('end', '>=', Carbon::now())
        ->whereNotIn('id', $participatedAzmonIds)
        ->with('course')
        ->get();
        

        // آمار کلی
        $course_count = Course::where('active', '1')
            ->where('private', '1')
            ->count();
        
        $konkor_count = Konkor::where('active', 1)
            ->count();
        
        // تعداد آزمون‌های فعال برای نمایش در کارت
        $active_exam_count = $activeExams->count();
        
        return view('student.index', compact(
            'user',
            'message',
            'activeExams',
            'active_exam_count',
            'course_count',
            'konkor_count'
        ))->with([
            'pageTitle' => 'صفحه دانشجو',
            'pageName' => 'دانشجو',
            'pageDescription' => 'خوش آمدید',
        ]);
    }

    public function courses()
    {
        $user = Auth::user();
        
        $courses = $user->courses()->get();
        
        return view('student.courses', compact('courses'));
    }

    /**
     * نمایش فعالیت‌های من در یک درس خاص
     */
    public function myActivities($courseId)
    {
        $user = Auth::user();
        
        // بررسی اینکه کاربر در این درس ثبت نام کرده باشد
        $isEnrolled = \App\Models\CourseUser::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->back()->with('error', 'شما در این درس ثبت نام نکرده‌اید.');
        }

        // دریافت اطلاعات درس
        $course = Course::findOrFail($courseId);

        // دریافت جلسات این درس
        $sessionIds = Session::where('course_id', $courseId)
            ->pluck('id')
            ->toArray();

        if (empty($sessionIds)) {
            return view('student.my-activities', [
                'course' => $course,
                'questions' => collect(),
                'discussions' => collect(),
                'exercises' => collect(),
                'stats' => $this->getEmptyStats()
            ]);
        }

        // ==========================================
        // 1. سوالات دانشجو در این درس
        // ==========================================
        $questions = Question::where('user_id', $user->id)
            ->whereIn('session_id', $sessionIds)
            ->with(['session.course'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($questions as $question) {
            $scores = ScoreQuestion::where('question_id', $question->id)->get();
            $question->scores_count = $scores->count();
            $question->approved_count = $scores->where('status', 'approved')->count();
            $question->display_status = $this->getDisplayStatus($question->status, $scores);
            $question->status_label = $this->getStatusLabel($question->status, $scores);
        }

        // ==========================================
        // 2. گزارش‌های دانشجو در این درس
        // ==========================================
        $discussions = Discussion::where('user_id', $user->id)
            ->whereIn('session_id', $sessionIds)
            ->with(['session.course'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($discussions as $discussion) {
            $scores = ScoreDiscussion::where('discussion_id', $discussion->id)->get();
            $discussion->scores_count = $scores->count();
            $discussion->approved_count = $scores->where('status', 'approved')->count();
            $discussion->display_status = $this->getDisplayStatus($discussion->status, $scores);
            $discussion->status_label = $this->getStatusLabel($discussion->status, $scores);
        }

        // ==========================================
        // 3. تکالیف دانشجو در این درس
        // ==========================================
        $exercises = ExerciseAnswer::where('user_id', $user->id)
            ->whereHas('exercise', function($query) use ($sessionIds) {
                $query->whereIn('session_id', $sessionIds);
            })
            ->with(['exercise.session.course'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($exercises as $exercise) {
            $scores = ScoreExercise::where('exercise_answer_id', $exercise->id)->get();
            $exercise->scores_count = $scores->count();
            $exercise->approved_count = $scores->where('status', 'approved')->count();
            $exercise->display_status = $this->getDisplayStatus($exercise->status, $scores);
            $exercise->status_label = $this->getStatusLabel($exercise->status, $scores);
        }

        // آمار کلی
        $stats = [
            'total_questions' => $questions->count(),
            'total_discussions' => $discussions->count(),
            'total_exercises' => $exercises->count(),
            'pending_questions' => $questions->where('display_status', 'pending')->count(),
            'pending_discussions' => $discussions->where('display_status', 'pending')->count(),
            'pending_exercises' => $exercises->where('display_status', 'pending')->count(),
            'approved_questions' => $questions->where('display_status', 'approved')->count(),
            'approved_discussions' => $discussions->where('display_status', 'approved')->count(),
            'approved_exercises' => $exercises->where('display_status', 'approved')->count(),
            'rejected_questions' => $questions->where('display_status', 'rejected')->count(),
            'rejected_discussions' => $discussions->where('display_status', 'rejected')->count(),
            'rejected_exercises' => $exercises->where('display_status', 'rejected')->count(),
        ];

        return view('student.my-activities', compact('course', 'questions', 'discussions', 'exercises', 'stats'));
    }

    /**
     * دریافت وضعیت نمایشی
     */
    private function getDisplayStatus($status, $scores)
    {
        if ($status !== null && $status !== 0) {
            return 'approved';
        }

        if ($status === 0) {
            return 'returned';
        }

        if ($scores->count() > 0) {
            $approvedCount = $scores->where('status', 'approved')->count();
            $rejectedCount = $scores->where('status', 'rejected')->count();
            
            if ($rejectedCount > 0) {
                return 'rejected';
            }
            
            if ($approvedCount > 0 && $approvedCount < 3) {
                return 'pending';
            }
            
            if ($approvedCount >= 3) {
                return 'approved';
            }
        }

        return 'pending';
    }

    /**
     * دریافت برچسب وضعیت
     */
    private function getStatusLabel($status, $scores)
    {
        if ($status !== null && $status !== 0) {
            $labels = [
                1 => 'عالی',
                2 => 'خوب',
                3 => 'متوسط',
                4 => 'بد',
            ];
            return $labels[$status] ?? 'نامشخص';
        }

        if ($status === 0) {
            return 'برگشت خورده';
        }

        if ($scores->count() > 0) {
            $approvedCount = $scores->where('status', 'approved')->count();
            $rejectedCount = $scores->where('status', 'rejected')->count();
            
            if ($rejectedCount > 0) {
                return 'رد شده';
            }
            
            if ($approvedCount >= 3) {
                return 'تایید شده';
            }
        }

        return 'در انتظار داوری';
    }

    /**
     * آمار خالی
     */
    private function getEmptyStats()
    {
        return [
            'total_questions' => 0,
            'total_discussions' => 0,
            'total_exercises' => 0,
            'pending_questions' => 0,
            'pending_discussions' => 0,
            'pending_exercises' => 0,
            'approved_questions' => 0,
            'approved_discussions' => 0,
            'approved_exercises' => 0,
            'rejected_questions' => 0,
            'rejected_discussions' => 0,
            'rejected_exercises' => 0,
        ];
    }
    /**
     * مشاهده جزئیات سوال (دانشجو)
     */
    public function viewQuestion($id)
    {
        $question = Question::with(['user', 'session.course'])
            ->findOrFail($id);

        // بررسی دسترسی (فقط دانشجو و فقط سوال خودش)
        if (Auth::id() != $question->user_id) {
            return redirect()->back()->with('error', 'شما دسترسی به این سوال ندارید.');
        }

        // دریافت داوری‌ها
        $scores = ScoreQuestion::where('question_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // محاسبه میانگین
        $approvedScores = ScoreQuestion::where('question_id', $id)
            ->where('status', 'approved')
            ->pluck('score')
            ->toArray();

        $averageScore = count($approvedScores) > 0 
            ? round(array_sum($approvedScores) / count($approvedScores), 2) 
            : null;

        $statusLabels = [
            null => 'در انتظار داوری',
            0 => 'برگشت خورده',
            1 => 'عالی',
            2 => 'خوب',
            3 => 'متوسط',
            4 => 'بد',
        ];

        return view('student.question-detail', compact(
            'question',
            'scores',
            'averageScore',
            'statusLabels'
        ))->with([
            'pageTitle' => 'جزئیات سوال',
            'pageName' => 'سوال',
            'pageDescription' => 'مشاهده جزئیات سوال',
        ]);
    }

    /**
     * مشاهده جزئیات گزارش (دانشجو)
     */
    public function viewDiscussion($id)
    {
        $discussion = Discussion::with(['user', 'session.course'])
            ->findOrFail($id);

        // بررسی دسترسی
        if (Auth::id() != $discussion->user_id) {
            return redirect()->back()->with('error', 'شما دسترسی به این گزارش ندارید.');
        }

        $scores = ScoreDiscussion::where('discussion_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedScores = ScoreDiscussion::where('discussion_id', $id)
            ->where('status', 'approved')
            ->pluck('score')
            ->toArray();

        $averageScore = count($approvedScores) > 0 
            ? round(array_sum($approvedScores) / count($approvedScores), 2) 
            : null;

        $statusLabels = [
            null => 'در انتظار داوری',
            0 => 'برگشت خورده',
            1 => 'عالی',
            2 => 'خوب',
            3 => 'متوسط',
            4 => 'بد',
        ];

        return view('student.discussion-detail', compact(
            'discussion',
            'scores',
            'averageScore',
            'statusLabels'
        ))->with([
            'pageTitle' => 'جزئیات گزارش',
            'pageName' => 'گزارش',
            'pageDescription' => 'مشاهده جزئیات گزارش',
        ]);
    }
    /**
     * مشاهده جزئیات تکلیف (دانشجو)
     */
    public function viewExercise($id)
    {
        $exercise = ExerciseAnswer::with(['user', 'exercise.session.course'])
            ->findOrFail($id);

        // بررسی دسترسی
        if (Auth::id() != $exercise->user_id) {
            return redirect()->back()->with('error', 'شما دسترسی به این تکلیف ندارید.');
        }

        $scores = ScoreExercise::where('exercise_answer_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedScores = ScoreExercise::where('exercise_answer_id', $id)
            ->where('status', 'approved')
            ->pluck('score')
            ->toArray();

        $averageScore = count($approvedScores) > 0 
            ? round(array_sum($approvedScores) / count($approvedScores), 2) 
            : null;

        $statusLabels = [
            'returned' => 'برگشت خورده',
            'scored' => 'ارزیابی شده',
            null => 'در انتظار داوری',
        ];

        return view('student.exercise-detail', compact(
            'exercise',
            'scores',
            'averageScore',
            'statusLabels'
        ))->with([
            'pageTitle' => 'جزئیات تکلیف',
            'pageName' => 'تکلیف',
            'pageDescription' => 'مشاهده جزئیات تکلیف',
        ]);
    }

    /**
     * نمایش پیشرفت درسی دانشجو در یک درس خاص
     */
    public function progress($courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);
        
        // بررسی اینکه کاربر در این درس ثبت نام کرده باشد
        $isEnrolled = \App\Models\CourseUser::where('user_id', $user->id)
            ->where('course_id', $courseId)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->back()->with('error', 'شما در این درس ثبت نام نکرده‌اید.');
        }
        
        $setting = Setting::where('course_id', $course->id)->first();
        $scorring = Scoring::where('course_id', $course->id)->first();
        
        if (!$setting || !$scorring) {
            return redirect()->back()->with('error', 'تنظیمات این درس کامل نیست.');
        }

        $sessions = Session::where('course_id', $course->id)->pluck('id');
        $max_session = $sessions->count();

        // ==========================================
        // 1. آمار سوالات
        // ==========================================
        $questions_all = Question::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->count();
            
        $questions_1 = Question::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 1)->count();
        $questions_2 = Question::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 2)->count();
        $questions_3 = Question::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 3)->count();
        $questions_4 = Question::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 4)->count();

        $questions = [
            '1' => $questions_1,
            '2' => $questions_2,
            '3' => $questions_3,
            '4' => $questions_4,
            'all' => $questions_all,
        ];

        // نمره سوالات
        $q_scores = 0;
        foreach (Question::where('user_id', $user->id)->whereIn('session_id', $sessions)->get() as $qq) {
            $q_scores += $qq->score;
        }
        $q_scores = ($questions_all != 0) ? $q_scores / ($questions_all * (20/25)) : 0;
        if ($q_scores > 20) $q_scores = 20;

        // نمره نهایی سوالات
        $q_nomre = ($scorring->q_1 * $questions_1) + ($scorring->q_2 * $questions_2) + 
                ($scorring->q_3 * $questions_3) + ($scorring->q_4 * $questions_4);
        $nomre_har_soal = $setting->tarahi_soal_nomre / ($setting->jalasat * ($setting->max_soal - 1));
        $q_nomre *= $nomre_har_soal;
        if ($q_nomre > $setting->tarahi_soal_nomre) {
            $q_nomre = $setting->tarahi_soal_nomre;
        }
        $nomre['q'] = round($q_nomre, 2);

        // ==========================================
        // 2. آمار گزارش‌ها
        // ==========================================
        $disc_all = Discussion::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->count();
            
        $disc_1 = Discussion::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 1)->count();
        $disc_2 = Discussion::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 2)->count();
        $disc_3 = Discussion::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 3)->count();
        $disc_4 = Discussion::where('user_id', $user->id)
            ->whereIn('session_id', $sessions)
            ->where('status', 4)->count();

        $discs = [
            '1' => $disc_1,
            '2' => $disc_2,
            '3' => $disc_3,
            '4' => $disc_4,
            'all' => $disc_all,
        ];

        // نمره گزارش‌ها
        $d_scores = 0;
        foreach (Discussion::where('user_id', $user->id)->whereIn('session_id', $sessions)->get() as $dd) {
            $d_scores += $dd->score;
        }
        $d_scores = ($disc_all != 0) ? $d_scores / ($disc_all * (20/25)) : 0;
        if ($d_scores > 20) $d_scores = 20;

        // نمره نهایی گزارش‌ها
        $d_nomre = ($scorring->d_1 * $disc_1) + ($scorring->d_2 * $disc_2) + 
                ($scorring->d_3 * $disc_3) + ($scorring->d_4 * $disc_4);
        if ($d_nomre > $setting->ersal_gozaresh_nomre) {
            $d_nomre = $setting->ersal_gozaresh_nomre;
        }
        $nomre_har_gozaresh = $setting->ersal_gozaresh_nomre / $setting->jalasat;
        $d_nomre *= $nomre_har_gozaresh;
        $nomre['d'] = round($d_nomre, 2);

        // ==========================================
        // 3. آمار تکالیف
        // ==========================================
        $exercises = Exercise::whereIn('session_id', $sessions)->pluck('id');
        
        $exer_1 = ExerciseAnswer::where('user_id', $user->id)
            ->whereIn('exercise_id', $exercises)
            ->where('status', 1)->count();
        $exer_2 = ExerciseAnswer::where('user_id', $user->id)
            ->whereIn('exercise_id', $exercises)
            ->where('status', 2)->count();
        $exer_3 = ExerciseAnswer::where('user_id', $user->id)
            ->whereIn('exercise_id', $exercises)
            ->where('status', 3)->count();
        $exer_4 = ExerciseAnswer::where('user_id', $user->id)
            ->whereIn('exercise_id', $exercises)
            ->where('status', 4)->count();
        $exer_all = ExerciseAnswer::where('user_id', $user->id)
            ->whereIn('exercise_id', $exercises)
            ->count();

        $exers = [
            '1' => $exer_1,
            '2' => $exer_2,
            '3' => $exer_3,
            '4' => $exer_4,
            'all' => $exer_all,
        ];

        // نمره نهایی تکالیف
        $e_nomre = ($scorring->e_1 * $exer_1) + ($scorring->e_2 * $exer_2) + 
                ($scorring->e_3 * $exer_3) + ($scorring->e_4 * $exer_4);
        if ($e_nomre > $setting->taklif_seminar_nomre) {
            $e_nomre = $setting->taklif_seminar_nomre;
        }
        $nomre['e'] = round($e_nomre, 2);

        // ==========================================
        // 4. آمار خودآزمایی
        // ==========================================
        $num_azmoon = Quiz::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->pluck('id');
        $count_azmoon = count($num_azmoon);

        $qu_scores = 0;
        foreach (Quiz::where('course_id', $course->id)->where('user_id', $user->id)->get() as $qq) {
            $qu_scores += $qq->score;
        }
        $qu_scores = ($questions_all != 0) ? $qu_scores / $questions_all : 0;
        if ($qu_scores > 20) $qu_scores = 20;

        // ==========================================
        // 5. آمار داوری
        // ==========================================
        $all_q = Question::whereIn('session_id', $sessions)->pluck('id');
        $all_d = Discussion::whereIn('session_id', $sessions)->pluck('id');

        $davari_q = Score::withTrashed()
            ->whereIn('sub_id', $all_q)
            ->where('type', 1)
            ->where('user_id', $user->id)
            ->count();

        $davari_gozaresh = Score::withTrashed()
            ->whereIn('sub_id', $all_d)
            ->where('type', 2)
            ->where('user_id', $user->id)
            ->count();

        $davarii = [
            'q' => $davari_q,
            'gozaresh' => $davari_gozaresh,
        ];

        // ==========================================
        // 6. محاسبات نهایی
        // ==========================================
        
        // فعالیت کلاسی
        $score_soal = min(8, ($questions_all * 8) / ($max_session * $setting->max_soal * 5 / 6));
        $score_gozaresh = min(5, $disc_all * 5 / $max_session);
        $score_davari = min(8, (($davarii['q'] + $davarii['gozaresh']) * 8) / ($max_session * (1 + $setting->max_soal) * 3));
        $score_azmoon = min(9, $count_azmoon * 9 / ($setting->min_w_khod * $max_session));
        
        $kelasi = min(30, $score_soal + $score_gozaresh + $score_azmoon + $score_davari);

        // پیشرفت درسی
        $score_pish_soal = ($score_soal > 0) ? min(12, $q_scores * 12 / $score_soal) : 0;
        $score_pish_gozaresh = ($score_gozaresh > 0) ? min(10, $d_scores * 10 / $score_gozaresh) : 0;
        $score_pish_azmoon = ($score_azmoon > 0) ? min(24, ($qu_scores / 24) * $score_azmoon) : 0;
        $score_keifiat = min(14, ((($q_scores + $d_scores + $qu_scores + 5) / 4) * 14));
        
        $pishraft = min(70, $score_pish_soal + $score_pish_gozaresh + $score_pish_azmoon + 5 + $score_keifiat);

        // ارزشیابی مستمر
        $mostamer = ($pishraft + $kelasi) * 12 / 100;
        if ($mostamer > 12) $mostamer = 12;
        $mostamer_score = ($setting->mostamar_nomre > 0) ? ($mostamer * 20 / $setting->mostamar_nomre) : 0;

        // نمره تلاش
        $nomre_har_talash = 5;
        $talash_soal = min(5, $nomre_har_talash * $questions_all / ($setting->jalasat * ($setting->max_soal - 1)));
        $talash_gozaresh = min(5, $nomre_har_talash * $disc_all / $setting->jalasat);
        $talash_davari_soal = min(5, $nomre_har_talash * $davarii['q'] / ($setting->jalasat * 2 * 6));
        $talash_davari_gozaresh = min(5, $nomre_har_talash * $davarii['gozaresh'] / ($setting->jalasat * 6));
        $talash_khod = min(5, $nomre_har_talash * $count_azmoon / ($setting->jalasat * (7 * 5)));
        
        $nomre['talash'] = round($talash_davari_soal + $talash_davari_gozaresh + $talash_soal + $talash_gozaresh + $talash_khod, 2);

        // نمره نهایی
        $nomre['pish'] = round($pishraft, 2);
        $nomre['total'] = $pishraft + $d_nomre + $q_nomre + $e_nomre + $nomre['talash'];

        // نمره پایان ترم
        $final = Amali::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->where('type', 1)
            ->first();
        $nomre['final'] = $final ? $final->nomre : null;

        // حداکثر نمرات برای نمودار
        $maxScores = [
            'q' => $setting->tarahi_soal_nomre,
            'd' => $setting->ersal_gozaresh_nomre,
            'e' => $setting->taklif_seminar_nomre,
            'pish' => $setting->pishraft_nomre,
            'talash' => $setting->talash_nomre,
        ];

        return view('student.progress', compact(
            'course',
            'user',
            'setting',
            'questions',
            'discs',
            'exers',
            'nomre',
            'count_azmoon',
            'davarii',
            'max_session',
            'q_scores',
            'qu_scores',
            'd_scores',
            'kelasi',
            'pishraft',
            'mostamer',
            'mostamer_score',
            'score_soal',
            'score_gozaresh',
            'score_davari',
            'score_azmoon',
            'score_pish_soal',
            'score_pish_gozaresh',
            'score_pish_azmoon',
            'score_keifiat',
            'maxScores'
        ))->with([
            'pageTitle' => 'پیشرفت درسی',
            'pageName' => 'پیشرفت درسی',
            'pageDescription' => 'مشاهده پیشرفت درسی شما در درس ' . $course->name,
        ]);
    }
}
