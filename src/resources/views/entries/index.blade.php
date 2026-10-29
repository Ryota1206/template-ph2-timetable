<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>時間割</title>
</head>
<body>
<main>
    <h1>自分の時間割</h1>
    <form action="/courses" method="GET">
        <button type="submit">授業を登録する</button>
    </form>
<table border ="1">
    <tr>
        <th>曜日</th>
        <th>時限</th>
        <th>授業名</th>
        <th>担当教員</th>
        <th>教室</th>
</tr>
@forelse ($entries as $entry)
<tr>
    <td>{{$entry->course->day}}</td>
    <td>{{$entry->course->period}}</td>
    <td>{{$entry->course->name}}</td>
    <td>{{$entry->course->teacher}}</td>
    <td>{{$entry->course->room}}</td>
    <td>
        <form action="/entries/{{$entry->id}}/edit"  method="GET">
</form>
</td>
<td>
    <form action="/entries/{{$entry->id}}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">削除</button>
</form>
</td>

</tr>
@empty
<tr>
    <td colspan="6">
        まだ授業が登録されていません
</td>
</tr>
@endforelse
</table>
</main>
</body>
</html>
