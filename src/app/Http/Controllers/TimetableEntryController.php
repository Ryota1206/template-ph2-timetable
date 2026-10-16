<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\TimetableEntry;

class TimetableEntryController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'day' => ['required', 'integer', 'between:1,7'],
            'period' => [
                'required', 'integer', 'between:1,255',
                Rule::unique('timetable_entries')->where('day', $request->input('day')),
            ],
            'title' => ['required', 'string', 'max:255'],
            'room' => ['nullable', 'string', 'max:255'],
        ], [
            'required' => ':attributeを入力してください。',
            'integer' => ':attributeは整数で入力してください。',
            'between' => ':attributeは:minから:maxの範囲で選択・入力してください。',
            'string' => ':attributeは文字列で入力してください。',
            'max' => ':attributeは:max文字以内で入力してください。',
            'period.unique' => 'この曜日・時限にはすでに授業が登録されています。',
        ], [
            'day' => '曜日',
            'period' => '時限',
            'title' => '授業名',
            'room' => '教室',
        ]);

        TimetableEntry::create($validated);
        return redirect('/entries');
    }
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
}
