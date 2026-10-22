<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\TimetableEntry;

class TimetableEntryController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'day'=>'required|integer|between:1,5',
            'period'=>'required|integer|between:1,6',
            'title'=>'required',
            'room'=>'nullable',
            ], [
            'day.required' => '曜日を入力してください。',
            'day.integer' => '1~5の整数で入力してください。',
            'day.between' => '曜日は1~5で入力してください',
            'period.required'=>'時限を入力してください',
            'period.integer'=>'1~6の整数で入力してください',
            'period.integer'=>'1~6の整数で入力してください',
            'title.required'=>'授業名を入力してください',
        ], [
            'day' => '曜日',
            'period' => '時限',
            'title' => '授業名',
            'room' => '教室',]);
            $taken =TimetableEntry::where('day',$request->input('day'))
            ->where('period',$request->input('period'))
            ->exists();
            if ($taken){
                return back()->withErrors([
                    'period'=>'その曜日・時限にはすでに授業が登録されています',
                ])->withInput();
            }
                    TimetableEntry::create($validated);
        return redirect('/entries');}
    public function index(){
        $entries =TimetableEntry::orderBy('day')
        ->orderBy('period')
        ->get();
        return view('entries.index',[
            'entries'=>$entries
        ]);
    }
    public function create()
    {
        return view('entries.create');
    }
    public function edit($id)
    {
        $entry=TimetableEntry::findOrFail($id);
        return view('entries.edit',['entry'=>$entry]);
    }
    public function update(Request $request,$id){
    $entry=TimetableEntry::findOrFail($id);
    $entry->update($request->only(
        ['day','period','title','room']
    )); $validated = $request->validate([
            'day'=>'required|integer|between:1,5',
            'period'=>'required|integer|between:1,6',
            'title'=>'required',
            'room'=>'nullable',
            ], [
            'day.required' => '曜日を入力してください。',
            'day.integer' => '1~5の整数で入力してください。',
            'day.between' => '曜日は1~5で入力してください',
            'period.required'=>'時限を入力してください',
            'period.integer'=>'1~6の整数で入力してください',
            'period.integer'=>'1~6の整数で入力してください',
            'title.required'=>'授業名を入力してください',
        ], [
            'day' => '曜日',
            'period' => '時限',
            'title' => '授業名',
            'room' => '教室',]);
            $taken =TimetableEntry::where('day',$request->input('day'))
            ->where('period',$request->input('period'))
            ->exists();
            if ($taken){
                return back()->withErrors([
                    'period'=>'その曜日・時限にはすでに授業が登録されています',
                ])->withInput();
            }
                    TimetableEntry::create($validated);
        return redirect('/entries');
    return redirect('/entries');
    }
    public function destroy($id){
        $entry =TimetableEntry::findOrFail($id);
        $entry->delete();
        return redirect('/entries');
    }
}
