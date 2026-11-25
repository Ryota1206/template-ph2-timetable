<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index(Request $request){
        DB::enableQueryLog();
        $keyword = $request->query('q');
        $keyword = is_string($keyword) ? trim($keyword) : '';
        $courseQuery = Course::with(['entries' => fn ($query) => $query->where('user_id', $request->user()->id)]);

        if ($keyword !== '') {
            // PostgreSQL の LIKE で、入力された記号を文字そのものとして扱う。
            $pattern = '%'.strtr($keyword, ['\\' => '\\\\', '%' => '\\%', '_' => '\\_']).'%';
            $courseQuery->where(function ($query) use ($pattern) {
                $query->where('name', 'like', $pattern)
                    ->orWhere('teacher', 'like', $pattern);
            });
        }

        $courses = $courseQuery->orderBy('day')
        ->orderBy('period')
        ->orderBy('id')
        ->get();
        $queries=DB::getQueryLog();
        Log::info('SQLの実行回数:'.count($queries));

        return view('courses.index',[
            'courses'=>$courses,
            'keyword'=>$keyword,
        ]);
    }
    public function show(Course $course)
    {
        $reviews = $course->reviews()->with('user')->get();

        return view('courses.show',[
            'course'=>$course,
            'reviews'=>$reviews,
        ]);
    }
}
