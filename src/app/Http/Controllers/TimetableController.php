<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    public function index(){
            $courses=DB::select(
    'SELECT period,name,teacher,room 
FROM courses
where day=1
order by period ASC'
);
$requiredCourses=DB::select(
    'SELECT name,credits
FROM courses
where required =true
order by code ASC'
);
return view('timetable',[
    'courses'=>$courses,
    'requiredCourses'=>$requiredCourses,
]);
    }
}
