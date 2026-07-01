<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AzmonController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\JudgmentController;
use App\Http\Controllers\QuestionReportController;
use App\Http\Controllers\Student\StudentCourseController;
use App\Http\Controllers\StudentSiteController;
use App\Http\Controllers\Teacher\CourseController;
use App\Http\Controllers\Teacher\StudentAdjectiveController;
use App\Http\Controllers\Teacher\StudentEventController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\Teacher\TeacherCourseController;
use App\Http\Controllers\TeacherSiteController;
use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

// مسیرهای عمومی
Route::get('/', [TestController::class, 'index'])->name('index');
Route::get('/quiz-course', [TestController::class, 'quizCourse'])->name('quizCourse');
Route::get('/add-course', [TestController::class, 'addCourse'])->name('addCourse');
Route::get('/publics', [TestController::class, 'publics'])->name('publics');
Route::get('/exams', [TestController::class, 'exams'])->name('exams');
Route::get('/surveys', [TestController::class, 'surveys'])->name('surveys');
Route::get('/content', [TestController::class, 'content'])->name('content');
Route::get('/create-quiz', [TestController::class, 'createQuiz'])->name('createQuiz');
Route::get('/quizzes', [TestController::class, 'quizzes'])->name('quizzes');
Route::get('/chats', [TestController::class, 'chats'])->name('chats');
Route::get('/change', [TestController::class, 'change'])->name('change');
Route::get('/azmoon',[TeacherSiteController::class,'azmoon'])->name('azmoon');
Route::get('/self-tests-list', [TestController::class, 'selfTestsList'])->name('selfTestsList');

// مسیرهای احراز هویت
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/loginPost', [AuthController::class, 'loginPost'])->name('loginPost');
Route::post('/registerPost', [AuthController::class, 'registerPost'])->name('registerPost');

Route::get("/", function () {
    return redirect("/login");
});

// test
Route::get('/role', [AuthController::class, 'roleFun']);

// ==========================================
// Teacher Routes
// ==========================================
Route::prefix('/teacher')->middleware(['role:teacher|admin'])->group(function () {
    Route::get('/', [TeacherSiteController::class, 'index'])->name('index_teacher');

    Route::prefix('/courses')->group(function () {
        Route::get('/', [TeacherSiteController::class, 'courses'])->name('courses');
        Route::get('/copy/{id}', [CourseController::class, 'getCopyData'])->name('courses.copy.data');
        Route::get('/view/{id}', [CourseController::class, 'view'])->name('view.coure');

        Route::get('/sessions/create/{id}', [CourseController::class, 'create'])->name('sessions.create');
        Route::post('/sessions/store/{id}', [CourseController::class, 'store'])->name('sessions.store');

        Route::post('/create', [CourseController::class, 'storeCourse'])->name('courses.store');

        Route::post('/toggle-status/{id}', [CourseController::class, 'toggleStatus'])->name('courses.toggle.status');
        Route::post('/toggle-archive/{id}', [CourseController::class, 'toggleArchive'])->name('courses.toggle.archive');
        Route::get('/archived', [CourseController::class, 'archivedCourses'])->name('courses.archived');

        Route::get('/student-profile/{id}', [CourseController::class, 'studentProfile'])->name('studentProfile');
        Route::post('/student-profile/{id}', [CourseController::class, 'updateStudentProfile'])->name('studentProfile.update');

        Route::get('/students-list/{id}', [CourseController::class, 'studentsList'])->name('studentsList');
        Route::get('/students/remove/{userId}/{courseId}', [CourseController::class, 'destroyUser'])->name('students.remove');
        Route::post('/students/restore/{userId}/{courseId}', [CourseController::class, 'restoreUser'])->name('students.restore');
        Route::get('/students/removed/{courseId}', [CourseController::class, 'removedStudents'])->name('students.removed');

        Route::get('/setting/{id}', [CourseController::class, 'setting'])->name('courses.setting');
        Route::post('/edit-setting', [CourseController::class, 'editSetting'])->name('courses.setting.update');

        Route::get('/adjectives/{studentId}', [StudentAdjectiveController::class, 'index']);
        Route::post('/adjectives', [StudentAdjectiveController::class, 'store']);

        Route::get('/events/{studentId}', [StudentEventController::class, 'index']);
        Route::post('/events', [StudentEventController::class, 'store']);

        Route::get('/student-evaluation/{courseId}/{userId}', [CourseController::class, 'studentEvaluation'])->name('studentEvaluation');
        
        Route::get('/grades-list/{id}', [CourseController::class, 'gradesList'])->name('gradesList');
        Route::post('/grades-save/{id}', [CourseController::class, 'saveGrades'])->name('grades.save');

        Route::get('/activities/{id}', [CourseController::class, 'allProgress'])->name('activities');
        Route::post('/activities-range/{id}', [CourseController::class, 'getStudentActivitiesRange'])->name('get.student.activities.range');

        // ===== گزارش‌های دانشجویان =====
        Route::get('/reports-list/{course_id}', [CourseController::class, 'reportsList'])->name('teacher.reports.list');
        Route::get('/report/{id}', [CourseController::class, 'getReportDetail'])->name('teacher.report.detail');

        // ===== فعالیت‌های دانشجویان =====
        Route::get('/student-activities/{course}', [CourseController::class, 'studentActivities'])->name('studentActivities');
        Route::get('/student-questions/{id}', [CourseController::class, 'studentQuestions'])->name('studentQuestions');
        Route::get('/student-reports/{id}', [CourseController::class, 'studentReports'])->name('studentReports');
        Route::get('/student-homeworks/{id}', [CourseController::class, 'studentHomeworks'])->name('studentHomeworks');
        Route::get('/student-self-tests/{id}', [CourseController::class, 'studentSelfTests'])->name('studentSelfTests');
        Route::get('/student-official-exams/{id}', [CourseController::class, 'studentOfficialExams'])->name('studentOfficialExams');

        // ===== بانک سوالات =====
        Route::prefix('/bank')->group(function () {
            Route::get('/{id}', [CourseController::class, 'questionBank'])->name('question.bank');
            Route::post('/star/{id}', [CourseController::class, 'toggleStar'])->name('question.star');
        });

        // ===== نظرسنجی =====
        Route::prefix('/survey')->group(function () {
            Route::get('/{id}', [SurveyController::class, 'index'])->name('surveys.index');
            Route::post('/store', [SurveyController::class, 'store'])->name('survey.store');
            Route::get('/results/{id}', [SurveyController::class, 'results'])->name('survey.results');
            Route::get('/remove/{id}', [SurveyController::class, 'destroy'])->name('survey.destroy');
            Route::get('/active/{id}', [SurveyController::class, 'toggleActive'])->name('survey.toggle');
            Route::get('/edit/{id}', [SurveyController::class, 'edit'])->name('survey.edit');
            Route::post('/update/{id}', [SurveyController::class, 'update'])->name('survey.update');
        });

        // ===== آزمون‌ها =====
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

        // ===== تمرین (Exercise) - استاد =====
        Route::prefix('/exercises')->group(function () {
            Route::get('/show/{session_id}', [ExerciseController::class, 'show'])->name('exercise.show');
            Route::post('/create', [ExerciseController::class, 'create'])->name('exercise.create');
            Route::get('/edit', [ExerciseController::class, 'edit'])->name('exercise.edit');
            Route::put('/update/{id}', [ExerciseController::class, 'update'])->name('exercise.update');
            Route::get('/delete/{id}', [ExerciseController::class, 'delete'])->name('exercise.delete');
            Route::get('/answers/{exercise_id}', [ExerciseController::class, 'answersList'])->name('exercise.answers');
            Route::post('/score', [ExerciseController::class, 'score'])->name('exercise.score');
        });

        // ===== آپلود فایل =====
        Route::post('/upload/image', [FileController::class, 'uploadImage'])->name('upload.image');
        Route::post('/upload/video', [FileController::class, 'uploadVideo'])->name('upload.video');
    });

    // ==========================================
    // مسیرهای مربوط به سوالات - معلم
    // ==========================================
    Route::prefix('/questions')->middleware(['role:teacher|admin'])->group(function () {
        Route::get('/create', [ExamController::class, 'create'])->name('createQuestion');
        Route::post('/store', [ExamController::class, 'store'])->name('question.store');
        Route::get('/random/{count?}', [ExamController::class, 'getRandomQuestions'])->name('api.random.questions');
        Route::get('/show/{id}', [ExamController::class, 'show'])->name('question.show');
        Route::get('/show/{id}', [ExamController::class, 'getQuestionData'])->name('question.getData');
        Route::put('/status/{id}', [ExamController::class, 'updateStatusTe'])->name('question.updateStatus');
        Route::delete('/{id}', [ExamController::class, 'destroy'])->name('question.destroy');
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
});

// ==========================================
// Student Routes
// ==========================================
Route::prefix('/student')->middleware(['role:student|admin'])->group(function () {
    Route::get('/', [StudentSiteController::class, 'index'])->name('index_student');

    Route::prefix('/courses')->group(function () {
        Route::get('/', [StudentSiteController::class, 'courses'])->name('courses.st');
        Route::get('/view/{id}', [StudentCourseController::class, 'view'])->name('view.coure.St');

        Route::post('/join-course', [StudentCourseController::class, 'join'])->name('join.course');

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

    // ==========================================
    // خودآزمایی - دانشجو
    // ==========================================
    Route::prefix('/self-test')->group(function () {
        Route::get('/start/{course_id}', [ExamController::class, 'startSelfTest'])->name('student.selfTest.start');
        Route::post('/next', [ExamController::class, 'nextQuestion'])->name('student.selfTest.next');
        Route::get('/results', [ExamController::class, 'selfTestResults'])->name('student.selfTest.results');
        Route::get('/history', [ExamController::class, 'selfTestHistory'])->name('student.selfTest.history');
    });

    // ==========================================
    // آزمون رسمی - دانشجو
    // ==========================================
    Route::prefix('/exam')->group(function () {
        Route::post('/start', [AzmonController::class, 'startExam'])->name('exam.start');
        Route::post('/next', [AzmonController::class, 'nextExamQuestion'])->name('exam.next');
        Route::get('/results/{id}', [AzmonController::class, 'examResults'])->name('exam.results');
        Route::get('/history', [AzmonController::class, 'examHistory'])->name('exam.history');
    });

    // ==========================================
    // مسیرهای طرح سوال - دانشجو
    // ==========================================
    Route::prefix('/questions')->group(function () {
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
    Route::prefix('/judgment')->middleware(['role:student|admin'])->group(function () {
        Route::get('/', [JudgmentController::class, 'index'])->name('student.judgment.index');        
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
});

// ==========================================
// مسیرهای چت
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/messages/{chatId}', [ChatController::class, 'getMessages'])->name('chat.messages');
});