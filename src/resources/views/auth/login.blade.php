<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
<main>
    <h1>ログイン</h1>
    <form action="/login" method="POST" novalidate>
        @csrf
        <p>
            <label for="email">メールアドレス</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email">
            @error('email') <span role="alert" style="display: block">{{ $message }}</span> @enderror
        </p>
        <p>
            <label for="password">パスワード</label>
            <input id="password" name="password" type="password" autocomplete="current-password">
        </p>
        <button type="submit">ログインする</button>
    </form>
    <p><a href="/register">ユーザー登録はこちら</a></p>
</main>
</body>
</html>
