<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>授業を編集</title>
</head>
<body>
    <main>
        <form method="POST"
        action="/entries/{{$entry->id}}">
        @csrf
        @method('PUT')
        <h1>曜日</h1>
        <input name="day"
        value="{{$entry->day}}">
        <h1>時限</h1>
        <input name="period"
        value="{{$entry->period}}">
        <h1>授業名</h1>
        <input name="title"
        value="{{$entry->title}}">
        <h1>教室</h1>
        <input name="room"
        value="{{$entry->room}}">
        <button>保存する</button>
</form>
