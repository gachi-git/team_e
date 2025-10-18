<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>質問を投稿</title>
</head>
<body>
    <h1>質問を投稿する</h1>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <form action="{{ route('questions.store') }}" method="POST">
        @csrf
        <div>
            <label for="title">タイトル：</label><br>
            <input type="text" name="title" id="title" value="{{ old('title') }}">
            @error('title')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="content">内容：</label><br>
            <textarea name="content" id="content" rows="5">{{ old('content') }}</textarea>
            @error('content')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">投稿する</button>
    </form>
</body>
</html>



