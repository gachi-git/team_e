<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">質問の詳細</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10 px-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
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

            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                <span>投稿者：{{ $question->user->name ?? '不明' }}</span>
                <span>投稿日：{{ $question->created_at->format('Y/m/d H:i') }}</span>
            </div>

            <div x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
                <button @click="open = !open"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    回答する
                </button>

                <div x-show="open" x-transition class="mt-4">
                    <form action="{{ route('answers.store', $question->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea name="body" rows="4"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 text-gray-900 dark:text-gray-900"
                            placeholder="ここに回答を入力してください..."></textarea>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            回答を投稿
                        </button>
                    </form>
                </div>
            </div>
                    {{-- 回答一覧 --}}
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6 space-y-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">回答一覧</h2>
                @forelse ($question->answers as $answer)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <p class="text-gray-800 dark:text-gray-200 whitespace-pre-line">{{ $answer->body }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            投稿日時: {{ $answer->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-600 dark:text-gray-400">まだ回答がありません。</p>
                @endforelse
            </div>

            <div class="mt-6">
                <a href="{{ route('questions.index') }}"
                    class="inline-block px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 dark:bg-gray-700 dark:text-indigo-300 dark:hover:bg-gray-600 rounded-xl transition">
                    ← 質問一覧に戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
