<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>授業一覧を表示</title>
</head>
<body>
    <main>
        @if ($errors->any())
    <ul role="alert">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
        <table border="1">
            <tr>
                <th>曜日</th>
                <th>時限</th>
                <th>授業名</th>
                <th>担当教員</th>
                <th>教室</th>
                <th>登録状況</th>
</tr>
@foreach($courses as $course)
<tr>
    <td>{{$course->day}}</td>
    <td>{{$course->period}}</td>
    <td>{{$course->name}}</td>
    <td>{{$course->teacher}}</td>
    <td>{{$course->room}}</td>
    <td>@if($course->entries->isNotEmpty())
    登録済み
@else
<form method="POST" action="/entries">
    @csrf
    <input type="hidden" name="course_id" value="{{$course->id}}">
    <button type="submit">登録</button>
</form>
@endif</td>
</tr>
@endforeach
</table>
</main>
</body>
</html>