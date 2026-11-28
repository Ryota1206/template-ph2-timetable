<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseFavoriteController extends Controller
{
    public function store(Request $request,Course $course)
    {
        $user=$request->user();
        $this->verifyFormUser($request);
        DB::table('course_favorites')->insertOrIgnore([
            'user_id'=>$user->id,
            'course_id'=>$course->id,
        ]);
        return back();
    }
    public function destroy(Request $request,Course $course)
    {
        $user=$request->user();
        $this->verifyFormUser($request);
        DB::table('course_favorites')
    ->where('user_id',$user->id)
    ->where('course_id', $course->id)
    ->delete();
    return back();
    }

    private function verifyFormUser(Request $request): void
    {
        abort_unless(
            $request->input('favorite_form_user_id') === (string) $request->user()->id,
            403,
            '授業一覧を開き直してから操作してください。'
        );
    }
}
