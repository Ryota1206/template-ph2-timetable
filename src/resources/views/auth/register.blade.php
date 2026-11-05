<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー登録</title>
</head>
<body>
<main>
    <h1>ユーザー登録</h1>
    <form action="/register" method="POST" novalidate>
        @csrf
        <p>
            <label for="name">名前</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name">
            @error('name') <span role="alert" style="display: block">{{ $message }}</span> @enderror
        </p>
        <p>
            <label for="email">メールアドレス</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email">
            @error('email') <span role="alert" style="display: block">{{ $message }}</span> @enderror
        </p>
        <p>
            <label for="password">パスワード</label>
            <input id="password" name="password" type="password" autocomplete="new-password">
            @error('password') <span role="alert" style="display: block">{{ $message }}</span> @enderror
        </p>
        <p>
            <label for="password_confirmation">確認用パスワード</label>
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </p>
        <button type="submit">登録する</button>
    </form>
    <p><a href="/login">ログインはこちら</a></p>
</main>
</body>
</html>
