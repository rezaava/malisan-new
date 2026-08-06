<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Discussion;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\Role;
use App\Models\Scoring;
use App\Models\session;
use App\Models\Setting;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;

class StudentSkillController extends Controller
{
    public function courses()
    {
        $user = Auth::user();

        $skills = $user->courses()->where('courses.archieve', 0)->whereIn('courses.type', [1,2])->get();

        return view('student.skills', compact('skills'));
    }
    public function view($id)
    {
        $course = Course::with(['sessions' => function ($query) {
            $query->orderBy('number', 'desc');
        }, 'settings'])->whereIn('type', [1,2])->findOrFail($id);

        $user = Auth::user();
        $studentRole = Role::where('name', 'student')->first();
        $teacherRole = Role::where('name', 'teacher')->first();
        
        // بررسی اینکه آیا کاربر نقش دانشجو دارد یا خیر
        $isStudent = $user->hasRole('student');
        $setting = $course->settings;

        $courseUser = CourseUser::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();
        
        $member = ($courseUser) ? 1 : 0;
        $paid = ($course->price == 0 || ($courseUser && $courseUser->paid == 1)) ? 1 : 0;

        $sessionIdsForJudgment = Session::where('course_id', $id)->whereHas('course', function($query) {
            $query->whereIn('type', [1,2]);
        })->pluck('id');
        $pendingQuestionsCount = Question::whereNull('status')
            ->whereIn('session_id', $sessionIdsForJudgment)
            ->count();
        $pendingDiscussionsCount = Discussion::whereNull('status')
            ->whereIn('session_id', $sessionIdsForJudgment)
            ->count();
        $isJudment = ($pendingQuestionsCount > 0 || $pendingDiscussionsCount > 0);

        if ($isStudent) {
            // ===== بررسی active بودن کل دوره =====
            if ($course->active == 1) {
                // فقط جلسات فعال رو بگیر
                $sessions = $course->sessions->where('active', 1);
                
                if ($sessions->isEmpty()) {
                    $sessions = collect();
                } else {
                    $totalSessionsCount = $sessions->count();

                    // فیلتر بر اساس عضویت (باز شدن خودکار جلسات)
                    if ($member == 1) { // حذف شرط private
                        $now = Carbon::now();
                        $time = Carbon::parse($courseUser->created_at);
                        $diffInDays = $time->diffInDays($now);

                        // محاسبهٔ بازهٔ زمانی هر جلسه (به روز)
                        if (isset($course->length) && $course->length > 0 && isset($course->sessions_length) && $course->sessions_length > 0) {
                            $periodPerSession = $course->length / $course->sessions_length;
                        } else {
                            // fallback به دورهٔ قبلی
                            $periodPerSession = $course->period ?? 1;
                            if ($periodPerSession <= 0) $periodPerSession = 1;
                        }

                        $unlockedCount = min($totalSessionsCount, floor($diffInDays / $periodPerSession) + 1);

                        $sessions = $sessions->filter(function ($session) use ($unlockedCount) {
                            return $session->number <= $unlockedCount;
                        });

                        $sessions = $sessions->sortByDesc('number')->values();
                    }

                    // فیلتر برای کاربر غیرعضو
                    if ($member == 0) {
                        $sessions = $sessions->filter(function ($session) {
                            return $session->number == 1;
                        });
                    } 
                    // فیلتر برای کاربری که پرداخت نکرده
                    elseif ($paid == 0) {
                        $sessions = $sessions->filter(function ($session) {
                            return $session->number <= 4;
                        });
                    }
                }
            } else {
                // اگر دوره غیرفعال بود، هیچ جلسه‌ای نشون نده
                $sessions = collect();
            }
        } else {
            // برای استاد همه جلسات رو نشون بده (حتی غیرفعال)
            $sessions = $course->sessions;
            
            if ($sessions->isEmpty()) {
                $sessions = collect();
            }
        }

        $khodazmaii = 0;
        
        if (!$sessions->isEmpty() && $setting) {
            $sessionIds = $sessions->pluck('id');
            
            $statusFilter = match ($setting->sath_khod) {
                1 => [1],
                2 => [1, 2],
                3 => [2],
                default => null,
            };

            if ($statusFilter) {
                $questionCount = Question::whereIn('session_id', $sessionIds)
                    ->whereIn('status', $statusFilter)
                    ->count();
                
                $khodazmaii = ($questionCount >= $setting->q_num) ? 1 : 0;
            }
        }

        // ===== اضافه کردن وضعیت دسترسی به هر جلسه =====
        if (!$sessions->isEmpty()) {
            // پیدا کردن آخرین جلسه
            $lastSession = Session::where('course_id', $course->id)
                ->whereHas('course', function($query) {
                    $query->whereIn('type', [1,2]);
                })
                ->orderBy('id', 'desc')
                ->first();
            
            // دریافت تمام exerciseها برای این دوره
            $exerciseIds = Exercise::whereIn('session_id', $sessions->pluck('id'))
                ->pluck('session_id')
                ->toArray();
            
            foreach ($sessions as $index => $session) {
                $session['ex_count'] = Exercise::where('session_id', $session->id)->count();
                
                // تنظیم وضعیت‌های پیش‌فرض
                $session['taklif_last'] = 1;
                $session['gozaresh_last'] = 1;
                $session['soal_last'] = 1;
                
                // تنظیم وضعیت‌های دسترسی برای دکمه‌ها - پیش‌فرض همه فعال
                $session['can_question'] = true;
                $session['can_homework'] = true;
                $session['can_report'] = true;

                $hasExercise = in_array($session->id, $exerciseIds);
                if (!$hasExercise) {
                    $session['can_homework'] = false;
                }

                // اگر تنظیمات وجود دارد و محدودیت فعال است
                if ($setting) {
                    // محدودیت طرح سوال به آخرین جلسه
                    if ($setting->soal_last == 1) {
                        if (!$lastSession || $session->id != $lastSession->id) {
                            $session['can_question'] = false;
                            $session['soal_last'] = 0;
                        }
                    }
                    
                    // محدودیت ارسال تکلیف به آخرین جلسه
                    // فقط اگر تکلیف وجود داشته باشد و محدودیت فعال باشد
                    if ($setting->taklif_last == 1 && $hasExercise) {
                        if (!$lastSession || $session->id != $lastSession->id) {
                            $session['can_homework'] = false;
                            $session['taklif_last'] = 0;
                        }
                    }
                    
                    // محدودیت ارسال گزارش به آخرین جلسه
                    if ($setting->gozaresh_last == 1) {
                        if (!$lastSession || $session->id != $lastSession->id) {
                            $session['can_report'] = false;
                            $session['gozaresh_last'] = 0;
                        }
                    }
                }
            }
        }

        $course['sessions'] = $course->sessions()->whereHas('course', function($query) {
            $query->whereIn('type', [1,2]);
        })->count();
        $course['count'] = ($studentRole) 
            ? $course->users()->where('role_id', $studentRole->id)->whereHas('courses', function($query) {
                $query->whereIn('type', [1,2]);
            })->count() 
            : 0;

        if ($teacherRole) {
            $teacher = $course->users()->where('role_id', $teacherRole->id)->pluck('user_id')->first();
            $course['user'] = $teacher ? User::findOrFail($teacher) : null;
        }

        $course['students'] = ($studentRole)
            ? $course->users()->where('role_id', $studentRole->id)->take(5)->get()
            : collect();

        return view('student.skill', compact(
            'setting',
            'khodazmaii',
            'sessions',
            'course',
            'isJudment',
            'member',
            'paid'
        ))->with([
            'student' => (int) $isStudent,
        ]);
    }
}