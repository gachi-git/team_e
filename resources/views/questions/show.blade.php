<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">質問の詳細</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-10 px-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                {{ $question->title }}
            </h1>

            <p class="text-gray-700 dark:text-gray-300 mb-6 leading-relaxed">
                {!! nl2br(e($question->body)) !!}
            </p>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between text-sm text-gray-500 dark:text-gray-400">
                <span>投稿者：{{ $question->user->name ?? '不明' }}</span>
                <span>投稿日：{{ $question->created_at->format('Y/m/d H:i') }}</span>
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
