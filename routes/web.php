<?php

use App\Http\Controllers\AdminAngizeshController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\AdminSurveyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AzmonController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\StudentMessageController;
use App\Http\Controllers\JudgmentController;
use App\Http\Controllers\QuestionReportController;
use App\Http\Controllers\Student\StudentCourseController;
use App\Http\Controllers\Student\StudentSkillController;
use App\Http\Controllers\Admin\AdminCoinController;
use App\Http\Controllers\StudentSiteController;
use App\Http\Controllers\Teacher\CourseController;
use App\Http\Controllers\Teacher\SkillController;
use App\Http\Controllers\Teacher\StudentAdjectiveController;
use App\Http\Controllers\Teacher\StudentEventController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\TeacherSiteController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

// مسیرهای احراز هویت
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/complete-reset-password', [AdminController::class, 'resetPasswordComplete'])->name('complete-reset-password');

Route::post('/loginPost', [AuthController::class, 'loginPost'])->name('loginPost');
Route::post('/registerPost', [AuthController::class, 'registerPost'])->name('registerPost');

Route::post('/upload/video', [UploadController::class, 'uploadVideo'])->name('upload.video');
Route::post('/upload/image', [UploadController::class, 'uploadImage'])->name('upload.image');

Route::get("/", function () {
    return redirect("/login");
});

Route::middleware(['auth'])->group(function () {
    Route::get('/switch-to-student', [AuthController::class, 'switchToStudent'])->name('switch.to.student');
    Route::get('/switch-to-teacher', [AuthController::class, 'switchToTeacher'])->name('switch.to.teacher');
    Route::get('/switch-to-admin', [AuthController::class, 'switchToAdmin'])->name('switch.to.admin');
});

Route::prefix('/admin')->middleware(['role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index_admin');
    
    Route::prefix('/coin')->group(function () {
        Route::get('/', [AdminCoinController::class, 'index'])->name('admin.coin');
        Route::post('/store', [AdminCoinController::class, 'store'])->name('admin.coin.store');
        Route::post('/{id}', [AdminCoinController::class, 'update'])->name('admin.coin.update');
        Route::post('/{id}/toggle', [AdminCoinController::class, 'toggleActive'])->name('admin.coin.toggle');
    });

    Route::prefix('/courses')->group(function () {
        Route::get('/', [AdminCourseController::class, 'courses'])->name('courses.Ad');
        Route::get('/view/{id}', [AdminCourseController::class, 'view'])->name('view.coure.Ad');
    });

    Route::prefix('/angizesh')->group(function () {
        Route::get('/', [AdminAngizeshController::class, 'angizesh_index'])->name('admin_angizesh');
        Route::post('/store', [AdminAngizeshController::class, 'angizesh_store'])->name('admin_angizesh.store');
        Route::put('/update/{id}', [AdminAngizeshController::class, 'angizesh_update'])->name('admin_angizesh.update');
        Route::delete('/destroy/{id}', [AdminAngizeshController::class, 'angizesh_destroy'])->name('admin_angizesh.destroy');
        Route::get('/edit/{id}', [AdminAngizeshController::class, 'angizesh_edit'])->name('admin_angizesh.edit');
    });

    Route::prefix('/survey')->group(function () {
        Route::get('/',[AdminSurveyController::class, 'index'])->name('admin_survey');
        Route::get('/category/{id}',[AdminSurveyController::class, 'category'])->name('admin.survey.category');
        Route::get('/question/{id}',[AdminSurveyController::class, 'show'])->name('admin.survey.show');
        Route::post('/category/store',[AdminSurveyController::class, 'storeCategory'])->name('admin.survey.category.store');
        Route::put('/category/{id}',[AdminSurveyController::class, 'updateCategory'])->name('admin.survey.category.update');
        Route::delete('/category/{id}',[AdminSurveyController::class, 'deleteCategory'])->name('admin.survey.category.delete');
        Route::post('/category/{id}/deactivate-all',[AdminSurveyController::class, 'deactivateCategorySurveys'])->name('admin.survey.category.deactivate-all');
        Route::post('/toggle-student',[AdminSurveyController::class, 'toggleStudentSurvey'])->name('admin.toggle-student-survey');
        Route::post('/toggle-teacher',[AdminSurveyController::class, 'toggleTeacherSurvey'])->name('admin.toggle-teacher-survey');
        Route::get('/settings',[AdminSurveyController::class, 'getSettings'])->name('admin.get-settings');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/chat', [ChatController::class, 'index'])->name('admin.chat.index');
        Route::post('/chat/send', [ChatController::class, 'send'])->name('admin.chat.send');
        Route::get('/chat/messages/{chatId}', [ChatController::class, 'getMessages'])->name('admin.chat.messages');
        Route::post('/reset-user-password/{id}', [AdminController::class, 'resetPasswordRequest'])->name('reset-user-password');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'adminShowUsers'])->name('show-users-admin');
        Route::get('/limited', [AdminController::class, 'adminShowLimitedUsers'])->name('show-limited-users-admin');
        Route::get('/limit-user/{id}', [AdminController::class, 'limitUser'])->name('limit-user');
        Route::get('/unlimit-user/{id}', [AdminController::class, 'unlimitUser'])->name('unlimit-user');
    });
});

// ==========================================
// Teacher Routes
// ==========================================
Route::prefix('/teacher')->middleware(['role:teacher|admin'])->group(function () {
    Route::get('/', [TeacherSiteController::class, 'index'])->name('index_teacher');

    Route::prefix('/onboarding')->group(function () {
        Route::get('/', [SurveyController::class, 'teacherOnboardingSurvey'])->name('teacher.onboarding');
        Route::post('/submit', [SurveyController::class, 'submitTeacherOnboarding'])->name('teacher.onboarding.submit');
        Route::get('/skip', [SurveyController::class, 'skipTeacherOnboarding'])->name('teacher.onboarding.skip');
    });

    Route::prefix('/courses')->group(function () {
        Route::post('/create', [CourseController::class, 'storeCourse'])->name('courses.store');
        Route::post('/edit-setting', [CourseController::class, 'editSetting'])->name('update.setting.courses');
        Route::post('/toggle-visibility/{id}', [CourseController::class, 'toggleVisibility'])->name('courses.toggle.visibility');
        Route::post('/toggle-dore/{id}', [CourseController::class, 'toggleDore'])->name('courses.toggle.dore');

        // جلسات
        Route::prefix('/sessions')->group(function () {
            Route::get('/create/{id}', [CourseController::class, 'create'])->name('sessions.create');
            Route::post('/store/{id}', [CourseController::class, 'store'])->name('sessions.store');
            Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('sessions.edit');
            Route::put('/update/{id}', [CourseController::class, 'updateSession'])->name('sessions.update');
            Route::delete('/delete/{id}', [CourseController::class, 'destroySession'])->name('sessions.delete');
            Route::post('/toggle-active/{id}', [CourseController::class, 'toggleSessionActive'])->name('sessions.toggle.active');
            Route::post('/delete-file/{id}', [CourseController::class, 'deleteSessionFile'])->name('sessions.delete.file');
        });

        // تنظیمات
        Route::prefix('/settings')->group(function () {
            Route::post('/update-report-desc', [CourseController::class, 'updateReportDescription'])->name('teacher.settings.update_report_desc');
            Route::get('/get-report-desc', [CourseController::class, 'getReportDescription'])->name('teacher.settings.get_report_desc');
        });

        // عملیات اصلی
        Route::post('/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('/', [TeacherSiteController::class, 'courses'])->name('courses');
        Route::get('/copy/{id}', [CourseController::class, 'getCopyData'])->name('courses.copy.data');
        Route::get('/{id}/edit-data', [CourseController::class, 'getEditData'])->name('courses.edit.data');
        Route::get('/view/{id}', [CourseController::class, 'view'])->name('view.coure');
        Route::post('/toggle-status/{id}', [CourseController::class, 'toggleStatus'])->name('courses.toggle.status');
        Route::post('/toggle-archive/{id}', [CourseController::class, 'toggleArchive'])->name('courses.toggle.archive');
        Route::get('/archived', [CourseController::class, 'archivedCourses'])->name('courses.archived');

        // دانشجویان
        Route::get('/student-profile/{id}', [CourseController::class, 'studentProfile'])->name('studentProfile');
        Route::post('/student-profile/{id}', [CourseController::class, 'updateStudentProfile'])->name('studentProfile.update');
        Route::get('/students-list/{id}', [CourseController::class, 'studentsList'])->name('studentsList');
        Route::post('/students/remove/{userId}/{courseId}', [CourseController::class, 'destroyUser'])->name('students.remove');
        Route::post('/students/restore/{userId}/{courseId}', [CourseController::class, 'restoreUser'])->name('students.restore');
        Route::get('/students/removed/{courseId}', [CourseController::class, 'removedStudents'])->name('students.removed');
        Route::get('/setting/{id}', [CourseController::class, 'setting'])->name('courses.setting');

        // صفات و رویدادها
        Route::get('/adjectives/{studentId}', [StudentAdjectiveController::class, 'index']);
        Route::post('/adjectives', [StudentAdjectiveController::class, 'store']);
        Route::get('/events/{studentId}', [StudentEventController::class, 'index']);
        Route::post('/events', [StudentEventController::class, 'store']);

        // ارزیابی و نمرات
        Route::get('/student-evaluation/{courseId}/{userId}', [CourseController::class, 'studentEvaluation'])->name('studentEvaluation');
        Route::get('/grades-list/{id}', [CourseController::class, 'gradesList'])->name('gradesList');
        Route::post('/grades-save/{id}', [CourseController::class, 'saveGrades'])->name('grades.save');

        // فعالیت‌ها
        Route::get('/activities/{id}', [CourseController::class, 'allProgress'])->name('activities');
        Route::post('/activities-range/{id}', [CourseController::class, 'getStudentActivitiesRange'])->name('get.student.activities.range');

        // گزارش‌ها
        Route::get('/reports-list/{course_id}', [CourseController::class, 'reportsList'])->name('teacher.reports.list');
        Route::get('/report/{id}', [CourseController::class, 'getReportDetail'])->name('teacher.report.detail');

        // فعالیت‌های دانشجویان
        Route::get('/student-activities/{course}', [CourseController::class, 'studentActivities'])->name('studentActivities');
        Route::get('/student-questions/{id}/{courseid}', [CourseController::class, 'studentQuestions'])->name('studentQuestions');
        Route::get('/student-reports/{id}/{courseid}', [CourseController::class, 'studentReports'])->name('studentReports');
        Route::get('/student-homeworks/{id}/{courseid}', [CourseController::class, 'studentHomeworks'])->name('studentHomeworks');
        Route::get('/student-self-tests/{id}/{courseid}', [CourseController::class, 'studentSelfTests'])->name('studentSelfTests');
        Route::get('/student-official-exams/{id}/{courseid}', [CourseController::class, 'studentOfficialExams'])->name('studentOfficialExams');

        // بانک سوالات
        Route::prefix('/bank')->group(function () {
            Route::get('/{id}', [CourseController::class, 'questionBank'])->name('question.bank');
            Route::post('/star/{id}', [CourseController::class, 'toggleStar'])->name('question.star');
            Route::get('/edit/{id}', [CourseController::class, 'editQuestion'])->name('question.edit');
            Route::put('/update/{id}', [CourseController::class, 'updateQuestion'])->name('question.update');
        });

        // نظرسنجی
        Route::prefix('/survey')->group(function () {
            Route::get('/{id}', [SurveyController::class, 'index'])->name('surveys.index');
            Route::post('/store', [SurveyController::class, 'store'])->name('survey.store');
            Route::get('/results/{id}', [SurveyController::class, 'results'])->name('survey.results');
            Route::get('/remove/{id}', [SurveyController::class, 'destroy'])->name('survey.destroy');
            Route::get('/active/{id}', [SurveyController::class, 'toggleActive'])->name('survey.toggle');
            Route::get('/edit/{id}', [SurveyController::class, 'edit'])->name('survey.edit');
            Route::post('/update/{id}', [SurveyController::class, 'update'])->name('survey.update');
        });

        // آزمون‌ها
        Route::prefix('/azmon')->group(function () {
            Route::get('/list/{id}', [AzmonController::class, 'list'])->name('azmon.list');
            Route::get('/create', [AzmonController::class, 'create'])->name('azmon.create');
            Route::post('/create', [AzmonController::class, 'createPost'])->name('azmon.store');
            Route::get('/edit', [AzmonController::class, 'edit'])->name('azmon.edit');
            Route::put('/edit/{id}', [AzmonController::class, 'editPost'])->name('azmon.update');
            Route::get('/delete/{id}', [AzmonController::class, 'delete'])->name('azmon.delete');
            Route::post('/zarib/{id}', [AzmonController::class, 'toggleZarib'])->name('azmon.toggleZarib');
            Route::get('/stats/{id}', [AzmonController::class, 'azmonStats'])->name('azmon.stats');
        });

        // تمرین‌ها
        Route::prefix('/exercises')->group(function () {
            Route::get('/show/{session_id}', [ExerciseController::class, 'show'])->name('exercise.show');
            Route::post('/create', [ExerciseController::class, 'create'])->name('exercise.create');
            Route::get('/edit', [ExerciseController::class, 'edit'])->name('exercise.edit');
            Route::put('/update/{id}', [ExerciseController::class, 'update'])->name('exercise.update');
            Route::get('/delete/{id}', [ExerciseController::class, 'delete'])->name('exercise.delete');
            Route::get('/answers/{exercise_id}/{userId?}', [ExerciseController::class, 'answersList'])->name('exercise.answers');
            Route::post('/score', [ExerciseController::class, 'score'])->name('exercise.score');
            Route::get('/correction/{courseId}', [CourseController::class, 'exerciseCorrection'])->name('exercises.correction');
            Route::get('/answers2/{exerciseId}', [CourseController::class, 'getExerciseAnswers'])->name('exercises.answers');
            Route::get('/questions/{sessionId}', [CourseController::class, 'getSessionQuestions'])->name('teacher.sessions.questions');
            Route::get('/discussions/{sessionId}', [CourseController::class, 'getSessionDiscussions'])->name('teacher.sessions.discussions');
            Route::post('/score/{answerId}', [CourseController::class, 'scoreExerciseAnswer'])->name('exercises.score');
        });

        // درخواست‌ها
        Route::get('/pending-requests/{courseId}', [TeacherSiteController::class, 'pendingRequests'])->name('courses.pending.requests');
        Route::post('/approve-request/{courseId}/{userId}', [TeacherSiteController::class, 'approveRequest'])->name('courses.approve.request');
        Route::post('/reject-request/{courseId}/{userId}', [TeacherSiteController::class, 'rejectRequest'])->name('courses.reject.request');
    });

    /*
    |--------------------------------------------------------------------------
    | روت‌های دوره‌های مهارتی (Skill) - فقط روت‌های اختصاصی
    |--------------------------------------------------------------------------
    */
    Route::prefix('/skill')->group(function () {
        // روت‌های اختصاصی Skill که با Courses تفاوت دارند
        Route::post('/create', [SkillController::class, 'storeCourse'])->name('skill.store');
        Route::post('/edit-setting', [SkillController::class, 'editSetting'])->name('update.setting.skill');
        Route::post('/toggle-visibility/{id}', [SkillController::class, 'toggleVisibility'])->name('skill.toggle.visibility');
        Route::post('/toggle-dore/{id}', [SkillController::class, 'toggleDore'])->name('skill.toggle.dore');

        // روت‌های اصلی
        Route::post('/{id}', [SkillController::class, 'update'])->name('skill.update');
        Route::delete('/{id}', [SkillController::class, 'destroy'])->name('skill.destroy');
        Route::get('/', [SkillController::class, 'skill'])->name('skill');
        Route::get('/copy/{id}', [SkillController::class, 'getCopyData'])->name('skill.copy.data');
        Route::get('/{id}/edit-data', [SkillController::class, 'getEditData'])->name('skill.edit.data');
        Route::get('/view/{id}', [SkillController::class, 'view'])->name('view.skill');
        Route::post('/toggle-status/{id}', [SkillController::class, 'toggleStatus'])->name('skill.toggle.status');
        Route::post('/toggle-archive/{id}', [SkillController::class, 'toggleArchive'])->name('skill.toggle.archive');
        Route::get('/archived', [SkillController::class, 'archivedCourses'])->name('skill.archived');

        // جلسات
        Route::prefix('/sessions')->group(function () {
            Route::get('/create/{id}', [SkillController::class, 'create'])->name('skill.sessions.create');
            Route::post('/store/{id}', [SkillController::class, 'store'])->name('skill.sessions.store');
            Route::get('/edit/{id}', [SkillController::class, 'edit'])->name('skill.sessions.edit');
            Route::put('/update/{id}', [SkillController::class, 'updateSession'])->name('skill.sessions.update');
            Route::delete('/delete/{id}', [SkillController::class, 'destroySession'])->name('skill.sessions.delete');
            Route::post('/toggle-active/{id}', [SkillController::class, 'toggleSessionActive'])->name('skill.sessions.toggle.active');
            Route::post('/delete-file/{id}', [SkillController::class, 'deleteSessionFile'])->name('skill.sessions.delete.file');
        });

        // تنظیمات
        Route::prefix('/settings')->group(function () {
            Route::post('/update-report-desc', [SkillController::class, 'updateReportDescription'])->name('skill.settings.update_report_desc');
            Route::get('/get-report-desc', [SkillController::class, 'getReportDescription'])->name('skill.settings.get_report_desc');
        });

        // دانشجویان
        Route::get('/student-profile/{id}', [SkillController::class, 'studentProfile'])->name('skill.studentProfile');
        Route::post('/student-profile/{id}', [SkillController::class, 'updateStudentProfile'])->name('skill.studentProfile.update');
        Route::get('/students-list/{id}', [SkillController::class, 'studentsList'])->name('skill.studentsList');
        Route::post('/students/remove/{userId}/{courseId}', [SkillController::class, 'destroyUser'])->name('skill.students.remove');
        Route::post('/students/restore/{userId}/{courseId}', [SkillController::class, 'restoreUser'])->name('skill.students.restore');
        Route::get('/students/removed/{courseId}', [SkillController::class, 'removedStudents'])->name('skill.students.removed');
        Route::get('/setting/{id}', [SkillController::class, 'setting'])->name('skill.setting');

        // ارزیابی و نمرات
        Route::get('/student-evaluation/{courseId}/{userId}', [SkillController::class, 'studentEvaluation'])->name('skill.studentEvaluation');
        Route::get('/grades-list/{id}', [CourseController::class, 'gradesList'])->name('skill.gradesList');
        Route::post('/grades-save/{id}', [SkillController::class, 'saveGrades'])->name('skill.grades.save');

        // فعالیت‌ها
        Route::get('/activities/{id}', [SkillController::class, 'allProgress'])->name('skill.activities');
        Route::post('/activities-range/{id}', [SkillController::class, 'getStudentActivitiesRange'])->name('skill.get.student.activities.range');

        // گزارش‌ها
        Route::get('/reports-list/{course_id}', [SkillController::class, 'reportsList'])->name('skill.teacher.reports.list');
        Route::get('/report/{id}', [SkillController::class, 'getReportDetail'])->name('skill.teacher.report.detail');

        // فعالیت‌های دانشجویان
        Route::get('/student-activities/{course}', [CourseController::class, 'studentActivities'])->name('skill.studentActivities');
        Route::get('/student-questions/{id}/{courseid}', [SkillController::class, 'studentQuestions'])->name('skill.studentQuestions');
        Route::get('/student-reports/{id}/{courseid}', [SkillController::class, 'studentReports'])->name('skill.studentReports');
        Route::get('/student-homeworks/{id}/{courseid}', [SkillController::class, 'studentHomeworks'])->name('skill.studentHomeworks');
        Route::get('/student-self-tests/{id}/{courseid}', [SkillController::class, 'studentSelfTests'])->name('skill.studentSelfTests');
        Route::get('/student-official-exams/{id}/{courseid}', [SkillController::class, 'studentOfficialExams'])->name('skill.studentOfficialExams');

        // بانک سوالات
        Route::prefix('/bank')->group(function () {
            Route::get('/{id}', [SkillController::class, 'questionBank'])->name('skill.question.bank');
            Route::post('/star/{id}', [SkillController::class, 'toggleStar'])->name('skill.question.star');
            Route::get('/edit/{id}', [SkillController::class, 'editQuestion'])->name('skill.question.edit');
            Route::put('/update/{id}', [SkillController::class, 'updateQuestion'])->name('skill.question.update');
        });

        // نظرسنجی
        Route::prefix('/survey')->group(function () {
            Route::get('/{id}', [SurveyController::class, 'index'])->name('skill.surveys.index');
            Route::post('/store', [SurveyController::class, 'store'])->name('skill.survey.store');
            Route::get('/results/{id}', [SurveyController::class, 'results'])->name('skill.survey.results');
            Route::get('/remove/{id}', [SurveyController::class, 'destroy'])->name('skill.survey.destroy');
            Route::get('/active/{id}', [SurveyController::class, 'toggleActive'])->name('skill.survey.toggle');
            Route::get('/edit/{id}', [SurveyController::class, 'edit'])->name('skill.survey.edit');
            Route::post('/update/{id}', [SurveyController::class, 'update'])->name('skill.survey.update');
        });

        // آزمون‌ها
        Route::prefix('/azmon')->group(function () {
            Route::get('/list/{id}', [AzmonController::class, 'list'])->name('skill.azmon.list');
            Route::get('/create', [AzmonController::class, 'create'])->name('skill.azmon.create');
            Route::post('/create', [AzmonController::class, 'createPost'])->name('skill.azmon.store');
            Route::get('/edit', [AzmonController::class, 'edit'])->name('skill.azmon.edit');
            Route::put('/edit/{id}', [AzmonController::class, 'editPost'])->name('skill.azmon.update');
            Route::get('/delete/{id}', [AzmonController::class, 'delete'])->name('skill.azmon.delete');
            Route::post('/zarib/{id}', [AzmonController::class, 'toggleZarib'])->name('skill.azmon.toggleZarib');
            Route::get('/stats/{id}', [AzmonController::class, 'azmonStats'])->name('skill.azmon.stats');
        });

        // تمرین‌ها
        Route::prefix('/exercises')->group(function () {
            Route::get('/show/{session_id}', [ExerciseController::class, 'show'])->name('skill.exercise.show');
            Route::post('/create', [ExerciseController::class, 'create'])->name('skill.exercise.create');
            Route::get('/edit', [ExerciseController::class, 'edit'])->name('skill.exercise.edit');
            Route::put('/update/{id}', [ExerciseController::class, 'update'])->name('skill.exercise.update');
            Route::get('/delete/{id}', [ExerciseController::class, 'delete'])->name('skill.exercise.delete');
            Route::get('/answers/{exercise_id}/{userId?}', [ExerciseController::class, 'answersList'])->name('skill.exercise.answers');
            Route::post('/score', [ExerciseController::class, 'score'])->name('skill.exercise.score');
            Route::get('/correction/{courseId}', [SkillController::class, 'exerciseCorrection'])->name('skill.exercises.correction');
            Route::get('/answers2/{exerciseId}', [SkillController::class, 'getExerciseAnswers'])->name('skill.exercises.answers');
            Route::get('/questions/{sessionId}', [SkillController::class, 'getSessionQuestions'])->name('skill.teacher.sessions.questions');
            Route::get('/discussions/{sessionId}', [SkillController::class, 'getSessionDiscussions'])->name('skill.teacher.sessions.discussions');
            Route::post('/score/{answerId}', [SkillController::class, 'scoreExerciseAnswer'])->name('skill.exercises.score');
        });

        // درخواست‌ها
        Route::get('/pending-requests/{courseId}', [TeacherSiteController::class, 'pendingRequests'])->name('skill.pending.requests');
        Route::post('/approve-request/{courseId}/{userId}', [TeacherSiteController::class, 'approveRequest'])->name('skill.approve.request');
        Route::post('/reject-request/{courseId}/{userId}', [TeacherSiteController::class, 'rejectRequest'])->name('skill.reject.request');
    });

    // ==========================================
    // مسیرهای مربوط به سوالات - معلم
    // ==========================================
    Route::prefix('/questions')->middleware(['role:teacher|admin'])->group(function () {
        Route::get('/create/{session_id}', [ExamController::class, 'studentCreate'])->name('student.question.create');
        Route::post('/store/{session_id}', [ExamController::class, 'studentStore'])->name('student.question.store');
        Route::get('/random/{count?}', [ExamController::class, 'getRandomQuestions'])->name('api.random.questions');
        Route::get('/show/{id}', [ExamController::class, 'show'])->name('question.show');
        Route::get('/show/{id}', [ExamController::class, 'getQuestionData'])->name('question.getData');
        Route::put('/status/{id}', [ExamController::class, 'updateStatusTe'])->name('question.updateStatus');
        Route::get('/{id}', [ExamController::class, 'destroy'])->name('question.destroy');
        Route::get('/list', [ExamController::class, 'list'])->name('question.list');
    });


    // ==========================================
    // مسیرهای گزارش ایراد سوال
    // ==========================================
    Route::get('/question-reports', [QuestionReportController::class, 'index'])->name('teacher.question.reports');
    Route::put('/question-report/{id}', [QuestionReportController::class, 'update'])->name('teacher.question.report.update');

    Route::prefix('/reports')->group(function () {
        Route::get('/list/{course_id}', [QuestionReportController::class, 'courseReports'])->name('teacher.question.reports.list');
    });

    // ==========================================
    // مسیرهای چت
    // ==========================================
    Route::middleware(['auth'])->group(function () {
        Route::get('/chat', [ChatController::class, 'index'])->name('teacher.chat.index');
        Route::post('/chat/send', [ChatController::class, 'send'])->name('teacher.chat.send');
        Route::get('/chat/messages/{chatId}', [ChatController::class, 'getMessages'])->name('teacher.chat.messages');
    });

    Route::get('/coin', [TeacherSiteController::class, 'coin'])->name('teacher.coin');
    Route::get('/profile', [TeacherSiteController::class, 'profile'])->name('teacher.profile');
    Route::post('/profile/update', [StudentSiteController::class, 'updateStudentProfile'])->name('teacherProfile.update');

    // ==========================================
    // مسیرهای پیام‌رسانی به دانشجو (Student Messages)
    // ==========================================
    Route::prefix('/student-messages')->group(function () {
        // ارسال پیام از طرف استاد
        Route::post('/send', [StudentMessageController::class, 'send'])->name('teacher.student-messages.send');
        
        // دریافت لیست پیام‌های یک دانشجو (برای استاد)
        Route::get('/{studentId}', [StudentMessageController::class, 'getMessages'])->name('teacher.student-messages.get');
        
        // دریافت تعداد پیام‌های خوانده نشده
        Route::get('/unread/count', [StudentMessageController::class, 'getUnreadCount'])->name('teacher.student-messages.unread');
        
        // علامت‌گذاری پیام به عنوان خوانده شده
        Route::post('/{messageId}/read', [StudentMessageController::class, 'markAsRead'])->name('teacher.student-messages.read');
    });
});

// ==========================================
// Student Routes
// ==========================================
Route::prefix('/student')->middleware(['role:student|admin|teacher'])->group(function () {
    Route::get('/', [StudentSiteController::class, 'index'])->name('index_student');

    Route::get('/coin', [StudentSiteController::class, 'coin'])->name('student.coin');
    Route::get('/profile', [StudentSiteController::class, 'profile'])->name('student.profile');
    Route::post('/profile/update', [StudentSiteController::class, 'updateStudentProfile'])->name('studentProfile.updatest');

    Route::prefix('/courses')->group(function () {
        Route::get('/', [StudentSiteController::class, 'courses'])->name('courses.st');
        Route::get('/view/{id}', [StudentCourseController::class, 'view'])->name('view.coure.St');

        Route::post('/join-course', [StudentCourseController::class, 'join_course'])->name('join.course');

        Route::get('/adjectives/{studentId}', [StudentAdjectiveController::class, 'index']);
        Route::post('/adjectives', [StudentAdjectiveController::class, 'store']);

        Route::get('/events/{studentId}', [StudentEventController::class, 'index']);
        Route::post('/events', [StudentEventController::class, 'store']);

        // ===== فعالیت‌های من =====
        Route::get('/my-activities/{course_id}', [StudentSiteController::class, 'myActivities'])->name('student.my.activities');

        // ===== مشاهده جزئیات =====
        Route::get('/question/{id}', [StudentSiteController::class, 'viewQuestion'])->name('student.question.view');
        Route::get('/discussion/{id}', [StudentSiteController::class, 'viewDiscussion'])->name('student.discussion.view');
        Route::get('/exercise/{id}', [StudentSiteController::class, 'viewExercise'])->name('student.exercise.view');

        // ===== پیشرفت درسی =====
        Route::get('/progress/{course_id}', [StudentSiteController::class, 'progress'])->name('student.progress');
    });

    Route::prefix('/skill')->group(function () {
        Route::get('/', [StudentSkillController::class, 'courses'])->name('skill.st');
        Route::get('/view/{id}', [StudentSkillController::class, 'view'])->name('view.skill.St');

        Route::post('/join-course', [StudentCourseController::class, 'join_skill'])->name('join.skill');

        Route::get('/adjectives/{studentId}', [StudentAdjectiveController::class, 'index']);
        Route::post('/adjectives', [StudentAdjectiveController::class, 'store']);

        Route::get('/events/{studentId}', [StudentEventController::class, 'index']);
        Route::post('/events', [StudentEventController::class, 'store']);

        // ===== فعالیت‌های من =====
        Route::get('/my-activities/{course_id}', [StudentSiteController::class, 'myActivities'])->name('student.my.activities.skill');

        // ===== مشاهده جزئیات =====
        Route::get('/question/{id}', [StudentSkillController::class, 'viewQuestion'])->name('student.question.view');
        Route::get('/discussion/{id}', [StudentSkillController::class, 'viewDiscussion'])->name('student.discussion.view');
        Route::get('/exercise/{id}', [StudentSkillController::class, 'viewExercise'])->name('student.exercise.view');

        // ===== پیشرفت درسی =====
        Route::get('/progress/{course_id}', [StudentSiteController::class, 'progress'])->name('student.progress.skill');
    });


    // ==========================================
    // خودآزمایی - دانشجو
    // ==========================================
    Route::prefix('/self-test')->group(function () {
        Route::get('/start/{course_id}', [ExamController::class, 'startSelfTest'])->name('student.selfTest.start');
        Route::post('/next', [ExamController::class, 'nextQuestion'])->name('student.selfTest.next');
        Route::get('/results/{quiz_id}', [ExamController::class, 'selfTestResults'])->name('student.selfTest.results');
        Route::get('/history', [ExamController::class, 'selfTestHistory'])->name('student.selfTest.history');
    });

    // ==========================================
    // آزمون رسمی - دانشجو
    // ==========================================
    Route::prefix('/exam')->group(function () {
        Route::get('/info/{id}', [AzmonController::class, 'getExamInfo'])->name('exam.info');
        Route::post('/verify-code/{id}', [AzmonController::class, 'verifyExamCode'])->name('exam.verify.code');
        Route::post('/start', [AzmonController::class, 'startExam'])->name('student.exam.start');
        Route::post('/next', [AzmonController::class, 'nextExamQuestion'])->name('exam.next');
        Route::get('/results/{id}', [AzmonController::class, 'examResults'])->name('exam.results');
        Route::get('/history', [AzmonController::class, 'examHistory'])->name('exam.history');
        Route::get('/check-access/{id}', [AzmonController::class, 'checkExamAccess'])->name('exam.check.access');
    });

    // ==========================================
    // مسیرهای طرح سوال - دانشجو
    // ==========================================
    Route::prefix('/questions')->group(function () {
        Route::get('/list/{session_id}', [ExamController::class, 'getSessionQuestions'])->name('student.questions.list');
        Route::get('/create/{session_id}', [ExamController::class, 'studentCreate'])->name('student.question.create');
        Route::post('/store/{session_id}', [ExamController::class, 'studentStore'])->name('student.question.store');
    });

    // ==========================================
    // پرسش اولیه برای دانشجو
    // ==========================================
    Route::prefix('/onboarding')->group(function () {
        Route::get('/', [SurveyController::class, 'onboardingSurvey'])->name('student.onboarding');
        Route::post('/submit', [SurveyController::class, 'submitOnboarding'])->name('student.onboarding.submit');
        Route::get('/skip', [SurveyController::class, 'skipOnboarding'])->name('student.onboarding.skip');
    });

    // ==========================================
    // مسیرهای تمرین - دانشجو
    // ==========================================
    Route::prefix('/exercise')->group(function () {
        Route::get('/show/{id}', [ExerciseController::class, 'studentShow'])->name('student.exercise.show');
        Route::post('/answer', [ExerciseController::class, 'answer'])->name('student.exercise.answer');
        Route::put('/answer/{id}', [ExerciseController::class, 'updateAnswer'])->name('student.exercise.answer.update');
        Route::get('/answer/{id}', [ExerciseController::class, 'deleteAnswer'])->name('student.exercise.answer.delete');
    });

    // ==========================================
    // مسیرهای گزارش - دانشجو
    // ==========================================
    Route::prefix('/discussion')->group(function () {
        Route::get('/create/{session_id}', [DiscussionController::class, 'create'])->name('student.discussion.create');
        Route::post('/store', [DiscussionController::class, 'store'])->name('student.discussion.store');
    });

    // ==========================================
    // مسیرهای داوری - دانشجو
    // ==========================================
    Route::prefix('/judgment')->group(function () {
        Route::get('/{course_id?}', [JudgmentController::class, 'index'])->name('student.judgment.index');
        Route::post('/store', [JudgmentController::class, 'store'])->name('student.judgment.store');
        Route::get('/stats', [JudgmentController::class, 'stats'])->name('student.judgment.stats');
        Route::get('/returned', [JudgmentController::class, 'returnedItems'])->name('student.judgment.returned');
        Route::post('/resubmit', [JudgmentController::class, 'resubmit'])->name('student.judgment.resubmit');
        Route::delete('/{id}', [JudgmentController::class, 'destroy'])->name('student.judgment.destroy');
    });

    // ==========================================
    // مسیرهای گزارش ایراد سوال - دانشجو
    // ==========================================
    Route::prefix('/question-report')->middleware(['role:student|admin'])->group(function () {
        Route::post('/store', [QuestionReportController::class, 'store'])->name('question.report.store');
    });

    // ==========================================
    // مسیرهای چت
    // ==========================================
    Route::middleware(['auth'])->group(function () {
        Route::get('/chat', [ChatController::class, 'index'])->name('student.chat.index');
        Route::post('/chat/send', [ChatController::class, 'send'])->name('student.chat.send');
        Route::get('/chat/messages/{chatId}', [ChatController::class, 'getMessages'])->name('student.chat.messages');
    });
    // ==========================================
    // مسیرهای پیام‌های دریافتی - دانشجو
    // ==========================================
    Route::prefix('/messages')->group(function () {
        // مشاهده همه پیام‌های دانشجو
        Route::get('/', [StudentMessageController::class, 'studentIndex'])->name('student.messages.index');
        
        // دریافت پیام‌ها به صورت JSON (برای AJAX)
        Route::get('/get', [StudentMessageController::class, 'studentGetMessages'])->name('student.messages.get');
        
        // علامت‌گذاری پیام به عنوان خوانده شده
        Route::post('/{messageId}/read', [StudentMessageController::class, 'markAsRead'])->name('student.messages.read');
    });
});


// ==========================================
// مسیر عمومی برای دریافت تعداد پیام‌های خوانده نشده (برای هدر)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/api/unread-messages-count', [StudentMessageController::class, 'getUnreadCount'])
        ->name('api.unread.messages.count');
});