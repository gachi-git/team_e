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
            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line mb-6">
                {{ $question->body }}
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-400">投稿者: 不明</p>
        </div>

        {{-- 回答投稿トグル --}}
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
    </div>
</x-app-layout>
