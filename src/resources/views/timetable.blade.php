<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>授業の詳細 | POSSE大学</title>
</head>
<main>
    <h1>時間割</h1>
<table>
    <thead>
        <tr>
            <th>時限</th>
            <th>授業名</th>
            <th>担当教員</th>
            <th>教室</th>
        </tr>
    </thead>

    <tbody>
        @foreach($courses as $course)
        <tr>
            <td>{{ $course->period }}</td>
            <td>{{ $course->name }}</td>
            <td>{{ $course->teacher }}</td>
            <td>{{ $course->room }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th>授業名</th>
            <th>単位数</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requiredCourses as $requiredCourse)
        <tr>
            <td>{{$requiredCourse->name}}</td>
            <td>{{$requiredCourse->credits}}</td>
        </tr>
        @endforeach
    </tbody>
</main>