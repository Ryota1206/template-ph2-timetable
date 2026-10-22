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
        <p>
            <label for="day">曜日</label>
            <select id="day" name="day" required>
                <option value="">選択してください</option>
                @foreach ([1 => '月曜日', 2 => '火曜日', 3 => '水曜日', 4 => '木曜日', 5 => '金曜日'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('day') == $value)>{{ $label }}</option>
                @endforeach
            </select>
        </p>
        <p>
            <label for="period">時限</label>
            <input id="period" name="period" type="number" min="1" max="6" value="{{ old('period') }}" required>
        </p>
        <p>
            <label for="title">授業名</label>
            <input id="title" name="title" maxlength="255" value="{{ old('title') }}" required>
        </p>
        <p>
            <label for="room">教室（任意）</label>
            <input id="room" name="room" maxlength="255" value="{{ old('room') }}">
        </p>
        <button type="submit">登録する</button>
    </form>
    <p><a href="/entries">時間割に戻る</a></p>
</main>
</body>
</html>
