<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class AdminSurveyController extends Controller
{
    public function angizesh_index()
    {
        $angizeshes = Survey::get();

        return view('admin.survey', compact('angizeshes', 'levelLabels'));
    }

}
