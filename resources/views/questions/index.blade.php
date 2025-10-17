
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>質問一覧</h1>

    {{-- 成功メッセージ --}}
    @if (session('success'))
        <div style="color: green; margin-bottom: 1em;">
            {{ session('success') }}
        </div>
    @endif

    {{-- 質問がない場合 --}}
    @if ($questions->isEmpty())
        <p>まだ質問が投稿されていません。</p>
    @else
        @foreach ($questions as $question)
            <div style="border: 1px solid #ccc; padding: 1em; margin-bottom: 1em; border-radius: 8px;">
                <h3>{{ $question->title }}</h3>
                <p>{{ $question->body }}</p>
                <small>投稿日時: {{ $question->created_at->format('Y-m-d H:i') }}</small>
            </div>
        @endforeach
    @endif
</div>
@endsection
