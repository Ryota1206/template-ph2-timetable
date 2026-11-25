<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>授業の詳細を表示</title>
</head>
<h1>授業名</h1>

<h1>{{$course->name}}</h1>
<h2>{{['月','火','水','木','金'][$course->day-1]}}曜</h2>
<h2>{{$course->period}}限</h2>
<h2>{{$course->teacher}}</h2>
<h2>{{$course->room}}</h2>

<h1>レビューを投稿する</h1>
<form action="/reviews" method="POST">
    @csrf

    <input type="hidden" name="course_id" value="{{$course->id}}">
    @error('course_id')
        <p role="alert">{{ $message }}</p>
    @enderror

    <label for="review-body">レビュー本文</label>
    <textarea id="review-body" name="body" @error('body') aria-invalid="true" aria-describedby="review-body-error" @enderror>{{ is_string(old('body')) ? old('body') : '' }}</textarea>
    @error('body')
        <p id="review-body-error" role="alert">{{ $message }}</p>
    @enderror

    <button type="submit">投稿する</button>
</form>

<h2>レビュー一覧</h2>
@forelse($reviews as $review)
<p>投稿者：{{$review->user->name}}</p>
<p>{{ $review->body}}</p>
@if(auth()->id()==$review->user_id)
<form action="/reviews/{{$review->id}}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">削除</button>
</form>
@endif
@empty
<p>まだレビューはありません。</p>
@endforelse
