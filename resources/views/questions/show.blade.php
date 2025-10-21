<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
            質問の詳細
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-6 space-y-8">

        {{-- 質問カード --}}
        <div class="bg-white dark:bg-gray-700 shadow-md rounded-2xl p-6 relative">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ $question->title }}
            </h1>

            <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                {!! nl2br(e($question->body)) !!}
            </p>

            @if($question->tags && $question->tags->isNotEmpty())
            <div class="mb-4 flex flex-wrap gap-2">
                @foreach ($question->tags as $tag)
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">
                    #{{ $tag->label }}
                </span>
                @endforeach
            </div>
            @endif

            <p class="text-sm text-gray-500 dark:text-gray-400">
                投稿者: {{ optional($question->user)->name ?? '不明' }}
            </p>

            <p class="text-sm text-gray-400 dark:text-gray-400 mb-2">
                閲覧数: {{ $question->views }} 回
            </p>

            {{-- 編集・削除ボタン（右下配置） --}}
            <div class="absolute bottom-4 right-4 flex gap-3">
                @can('update', $question)
                <a href="{{ route('questions.edit', $question->id) }}"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    編集
                </a>
                @endcan

                @can('delete', $question)
                <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                    onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        削除
                    </button>
                </form>
                @endcan
            </div>
        </div>

        {{-- 回答 or 返信投稿（投稿者だけ切り替え） --}}
        <div x-data="{ open: false }" class="bg-white dark:bg-gray-700 shadow-md rounded-2xl p-6">
            @if(Auth::check() && Auth::id() === $question->user_id)
                {{-- 投稿者本人には返信ボタンを表示 --}}
                <button
                    @click="open = !open"
                    class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    返信する
                </button>
            @else
                {{-- それ以外は回答ボタン --}}
                <button
                    @click="open = !open"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    回答する
                </button>
            @endif

            <div x-show="open" x-transition x-cloak class="mt-4">
                <form action="{{ route('answers.store', $question->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea
                        name="body" rows="4"
                        class="w-full rounded-lg p-3
                                border border-gray-300 dark:border-gray-600
                                text-gray-900 dark:text-gray-100
                                bg-white dark:bg-gray-700"
                        placeholder="ここに{{ Auth::check() && Auth::id() === $question->user_id ? '返信' : '回答' }}を入力してください..."></textarea>

                    <button type="submit"
                        class="px-4 py-2 {{ Auth::check() && Auth::id() === $question->user_id ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition">
                        {{ Auth::check() && Auth::id() === $question->user_id ? '返信を投稿' : '回答を投稿' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- ✅ 回答一覧 --}}
        <div class="bg-white dark:bg-gray-700 shadow-md rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">回答一覧</h2>

            @forelse ($question->answers as $answer)
                {{-- 投稿者の回答は黄色カード、それ以外は白カード --}}
                <div class="p-4 mb-4 rounded-2xl shadow transition hover:shadow-lg
                    {{ $answer->user_id === $question->user_id ? 'bg-yellow-50 dark:bg-yellow-900/40' : 'bg-white dark:bg-gray-800' }}">
                    
                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-line">{{ $answer->body }}</p>
                    <p class="text-sm text-gray-500 mt-2">
                        投稿日時: {{ $answer->created_at->format('Y/m/d H:i') }}
                        @if($answer->relationLoaded('user') && $answer->user)
                            ／ 投稿者: {{ $answer->user->name }}
                        @endif
                    </p>

                    {{-- ✅ ベストアンサー装飾 --}}
                    @if($question->best_answer_id === $answer->id)
                        <div class="mt-3 inline-block bg-green-100 text-green-800 text-sm font-semibold px-3 py-1 rounded-full">
                            🌟 ベストアンサー
                        </div>
                    @endif

                    {{-- ✅ 投稿者だけが押せるボタン --}}
                    @if(Auth::check() && Auth::id() === $question->user_id && $question->best_answer_id === null)
                        <form method="POST"
                            action="{{ route('answers.best', ['question' => $question->id, 'answer' => $answer->id]) }}"
                            class="mt-3"
                            x-data="{ loading: false }"
                            @submit="loading = true">
                            @csrf
                            <button type="submit"
                                x-bind:disabled="loading"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-lg shadow transition">
                                <span x-show="!loading">ベストアンサーにする</span>
                                <span x-show="loading" class="animate-pulse">処理中...</span>
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-gray-600 dark:text-gray-400">まだ回答がありません。</p>
            @endforelse
        </div>

        <div>
            <a href="{{ route('questions.index') }}"
                class="inline-block px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100
                        dark:bg-gray-700 dark:text-indigo-300 dark:hover:bg-gray-600 rounded-xl transition">
                ← 質問一覧に戻る
            </a>
        </div>
    </div>

    {{-- Alpine のフラッシュ抑止用 --}}
    @once
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @endonce
</x-app-layout>
