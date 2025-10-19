<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
            質問の詳細
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-6 space-y-8">

        {{-- 質問カード --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                {{ $question->title }}
            </h1>

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

            <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                {!! nl2br(e($question->body)) !!}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                投稿者: {{ optional($question->user)->name ?? '不明' }}
            </p>
        </div>

        <div class="mb-4 flex gap-4">
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


        {{-- 回答投稿（トグル1つだけ） --}}
        <div x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
            <button
                @click="open = !open"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                回答する
            </button>

            <div x-show="open" x-transition x-cloak class="mt-4">
                <form action="{{ route('answers.store', $question->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea
                        name="body" rows="4"
                        class="w-full rounded-lg p-3
                                border border-gray-300 dark:border-gray-600
                                text-gray-900 dark:text-gray-100
                                bg-white dark:bg-gray-700"
                        placeholder="ここに回答を入力してください..."></textarea>

                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        回答を投稿
                    </button>
                </form>
            </div>
        </div>

        {{-- 回答一覧（1回だけ） --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 space-y-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">回答一覧</h2>

            @forelse ($question->answers as $answer)
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $answer->body }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    投稿日時: {{ $answer->created_at->format('Y-m-d H:i') }}
                    @if($answer->relationLoaded('user') && $answer->user)
                    ／ 投稿者: {{ $answer->user->name }}
                    @endif
                </p>
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