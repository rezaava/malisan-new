<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentSiteController;
use App\Http\Controllers\Teacher\CourseController;
use App\Http\Controllers\Teacher\TeacherCourseController;
use App\Http\Controllers\TeacherSiteController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TestController;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/loginPost', [AuthController::class, 'loginPost'])->name('loginPost');
Route::post('/registerPost', [AuthController::class, 'registerPost'])->name('registerPost');



Route::get('/', [TestController::class, 'index'])->name('index');
Route::get('/courses', [TestController::class, 'courses'])->name('courses');
Route::get('/course', [TestController::class, 'course'])->name('course');
Route::get('/quiz-course', [TestController::class, 'quizCourse'])->name('quizCourse');
Route::get('/add-course', [TestController::class, 'addCourse'])->name('addCourse');
Route::get('/create-session', [TestController::class, 'createSession'])->name('createSession');
Route::get('/publics', [TestController::class, 'publics'])->name('publics');
Route::get('/exams', [TestController::class, 'exams'])->name('exams');
Route::get('/surveys', [TestController::class, 'surveys'])->name('surveys');
Route::get('/content', [TestController::class, 'content'])->name('content');
Route::get('/create-quiz', [TestController::class, 'createQuiz'])->name('createQuiz');
Route::get('/quizzes', [TestController::class, 'quizzes'])->name('quizzes');
Route::get('/chats', [TestController::class, 'chats'])->name('chats');
Route::get('/change', [TestController::class, 'change'])->name('change');
Route::get('/students-list', [TestController::class, 'studentsList'])->name('studentsList');
Route::get('/grades-list', [TestController::class, 'gradesList'])->name('gradesList');
Route::get('/activities', [TestController::class, 'activities'])->name('activities');
Route::get('/student-activities', [TestController::class, 'studentActivities'])->name('studentActivities');
Route::get('/student-profile', [TestController::class, 'studentProfile'])->name('studentProfile');
Route::get('/student-evaluation', [TestController::class, 'studentEvaluation'])->name('studentEvaluation');










Route::get("/", function () {
    return redirect("/login");
});

Route::get('/role', [AuthController::class, 'roleFun']);

Route::prefix('/teacher')->middleware(['role:teacher|admin'])->group(function () {

    Route::get('/', [TeacherSiteController::class, 'index'])->name('index_teacher');

    // courses
    Route::prefix('/courses')->middleware(['role:teacher|admin'])->group(function () {
        Route::get('/', [TeacherSiteController::class, 'courses'])->name('courses');
        Route::get('/copy/{id}', [CourseController::class, 'getCopyData'])->name('courses.copy.data');
        Route::post('/create', [CourseController::class, 'store'])->name('courses.store');
    });
});

Route::prefix('/student')->middleware(['role:student|admin'])->group(function () {
    Route::get('/', [StudentSiteController::class, 'index'])->name('index_student');
});
