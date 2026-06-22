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
}
