<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Gate;
class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $user_id=$request->user()->id;

        $validated=$request->validate([
            'course_id'=>'required|integer|exists:courses,id',
            'body'=>'required|string|max:1000',
        ], [
            'course_id' => '対象の授業を確認できませんでした。授業詳細ページを開き直してください。',
            'body.required' => 'レビュー本文を入力してください。',
            'body.string' => 'レビュー本文は文字で入力してください。',
            'body.max' => 'レビュー本文は1000文字以内で入力してください。',
        ]);

        Review::create([
            'user_id'=>$user_id,
            'course_id'=>$validated['course_id'],
            'body'=>$validated['body'],
        ]);

        return redirect('/courses/'.$validated['course_id']);
    }
    public function create()
    {
        return view('reviews.create');
    }
    public function destroy(Review $review)
    {
        Gate::authorize('delete',$review);
        $review->delete();
        return redirect('/courses/'.$review->course_id);
    }

}
