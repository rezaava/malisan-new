<?php

namespace App\Http\Controllers;

use App\Models\Angizesh;
use Illuminate\Http\Request;

class StudentSiteController extends Controller
{
    function index() {
        $message = Angizesh::whereIn('level',[7,8])->inRandomOrder()->first();
        return view('student.index',compact('message'));
    }
}
