<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

use App\Models\TimetableEntry;
use App\Models\Course;

class TimetableEntryController extends Controller
{
    public function store(Request $request){
        $validated=$request->validate([
            'course_id'=>'required|exists:courses,id',
        ],[
            'course_id.required'=>'授業を一覧から選んでください',
            'course_id.exists'=>'授業を一覧から選んでください',
        ]);
        $course=Course::findOrFail($request->input('course_id'));
        $taken=TimetableEntry::where('user_id', $request->user()->id)->whereHas('course',function($query)use($course){
            $query->where('day',$course->day)
            ->where('period',$course->period);
        })
        ->exists();
        if($taken){
            return back()->withErrors([
                'course_id'=>'その曜日・時限にはすでに授業が登録されています',
            ])->withInput();
        }
        TimetableEntry::create($validated + ['user_id' => $request->user()->id]);
        return redirect('/entries');
    }
    public function index(Request $request){
        $entries=TimetableEntry::where('user_id', $request->user()->id)->with('course')->get()
        ->sortBy(fn($entry)=>
        $entry->course->day*10
        +$entry->course->period);

        return view('entries.index',[
            'entries'=>$entries
        ]);
    }
    public function create(Request $request)
    {
        $courses=Course::with(['entries' => fn ($query) => $query->where('user_id', $request->user()->id)])
        ->orderBy('day')
        ->orderBy('period')
        ->orderBy('id')
        ->get();
        return view('entries.create',[
            'courses'=>$courses
        ]);
    }
    public function destroy($id){
        $entry =TimetableEntry::findOrFail($id);
        Gate::authorize('delete', $entry);
        $entry->delete();
        return redirect('/entries');
    }
}
