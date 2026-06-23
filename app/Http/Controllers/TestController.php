<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class TestController extends Controller
{
    //
    public function publics()
    {

        return view('publics');
    }

    public function exams()
    {

        return view('exams');
    }

    public function surveys()
    {

        return view('surveys');
    }

    public function content()
    {

        return view('content');
    }

    public function createQuiz()
    {

        return view('create-quiz');
    }

    public function quizzes()
    {

        return view('quizzes');
    }

    public function studentsList()
    {

        return view('students-list');
    }

    public function gradesList()

    {

        return view('grades-list');
    }

    public function activities()

    {

        return view('activities');
    }

    public function studentActivities()

    {

        return view('student-activities');
    }

    public function studentProfile()

    {

        return view('student-profile');
    }

    public function studentEvaluation()

    {

        return view('student-evaluation');
    }

    public function studentSetting()

    {

        return view('student-setting');
    }

    public function createQuestion()

    {

        return view('teacher.create-question');
    }

    public function selfTestsList()

    {

        return view('self-tests-list');
    }

    public function studentQuestions()

    {

        return view('student-questions');
    }

    public function studentReports()

    {

        return view('student-reports');
    }

    public function studentHomeworks()
    {
        return view('student-homeworks');
    }

    public function studentSelfTests()
    {
        return view('student-self-tests');
    }

    public function studentOfficialExams()
    {
        return view('student-official-exams');
    }
}
