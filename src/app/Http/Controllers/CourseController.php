<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index(){
        DB::enableQueryLog();
        $courses=Course::orderBy('day')
        ->orderBy('period')
        ->orderBy('id')
        ->get();
        $queries=DB::getQueryLog();
        Log::info('SQLの実行回数:'.count($queries));

        return view('courses.index',[
            'courses'=>$courses
        ]);
    }
}
