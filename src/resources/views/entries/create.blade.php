<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>授業を登録</title>
</head>
<body>
<main>
    <h1>授業を登録</h1>

    @if ($errors->any())
        <ul role="alert">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/entries">
        @csrf
        <select name="course_id">
            @foreach ($courses as $course)
            <option value="{{$course->id}}">
                {{['月','火','水','木','金'][$course->day-1]}}曜日
                {{$course->period}}限
                {{$course->name}}
                ({{$course->teacher}})
</option>
@endforeach
</select>
        <button type="submit">登録する</button>
    </form>
    <p><a href="/entries">時間割に戻る</a></p>
</main>
</body>
</html>
