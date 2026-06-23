<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Student\StudentCourseController;
use App\Http\Controllers\StudentSiteController;
use App\Http\Controllers\Teacher\CourseController;
use App\Http\Controllers\Teacher\StudentAdjectiveController;
use App\Http\Controllers\Teacher\StudentEventController;
use App\Http\Controllers\Teacher\TeacherCourseController;
use App\Http\Controllers\TeacherSiteController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;

Route::get('/', [TestController::class, 'index'])->name('index');
Route::get('/courses', [TestController::class, 'courses'])->name('courses');
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
Route::get('/grades-list', [TestController::class, 'gradesList'])->name('gradesList');
Route::get('/activities', [TestController::class, 'activities'])->name('activities');
Route::get('/student-activities', [TestController::class, 'studentActivities'])->name('studentActivities');
Route::get('/student-evaluation', [TestController::class, 'studentEvaluation'])->name('studentEvaluation');









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

// Teacher
Route::prefix('/teacher')->middleware(['role:teacher|admin'])->group(function () {
    Route::get('/', [TeacherSiteController::class, 'index'])->name('index_teacher');
    
    Route::prefix('/courses')->middleware(['role:teacher|admin'])->group(function () {
        Route::get('/', [TeacherSiteController::class, 'courses'])->name('courses');
        Route::get('/copy/{id}', [CourseController::class, 'getCopyData'])->name('courses.copy.data');
        Route::get('/view/{id}', [CourseController::class, 'view'])->name('view.coure');
        
        // مسیرهای ایجاد جلسه
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



        Route::get('/adjectives/{studentId}', [StudentAdjectiveController::class, 'index']);
        Route::post('/adjectives', [StudentAdjectiveController::class, 'store']);

        Route::get('/events/{studentId}', [StudentEventController::class, 'index']);
        Route::post('/events', [StudentEventController::class, 'store']);
    });
});

// Student
Route::prefix('/student')->middleware(['role:student|admin'])->group(function () {
    Route::get('/', [StudentSiteController::class, 'index'])->name('index_student');
        
        Route::prefix('/courses')->middleware(['role:student|admin'])->group(function () {
            Route::get('/', [StudentSiteController::class, 'courses'])->name('courses.st');
            Route::get('/view/{id}', [StudentCourseController::class, 'view'])->name('view.coure.St');

            Route::post('/join-course', [StudentCourseController::class, 'join'])->name('join.course');


            Route::get('/adjectives/{studentId}', [StudentAdjectiveController::class, 'index']);
            Route::post('/adjectives', [StudentAdjectiveController::class, 'store']);

            Route::get('/events/{studentId}', [StudentEventController::class, 'index']);
            Route::post('/events', [StudentEventController::class, 'store']);
    });
});

