<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
            質問を投稿する
        </h2>
    </x-slot>

    <main class="flex justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <!-- タイトル -->
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
                新しい質問を投稿
            </h1>

            <!-- 投稿フォーム -->
            <form method="POST" action="{{ route('questions.store') }}" class="space-y-6">
                @csrf

                <!-- タイトル入力 -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        タイトル
                    </label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        required
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="例：Laravelでバリデーションを実装するには？"
                        value="{{ old('title') }}"
                    >
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 内容入力 -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        内容
                    </label>
                    <textarea
                        id="body"
                        name="body"
                        rows="6"
                        required
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="質問内容を具体的に書いてください。">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ハッシュタグ（任意） -->
                <div>
                    <label for="hashtags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        ハッシュタグ（任意）
                    </label>
                    <input
                        id="hashtags"
                        name="hashtags"
                        type="text"
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="#○○大学 #◻︎部 など（カンマ/空白区切りも可）"
                        value="{{ old('hashtags') }}"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        例）<code>#九州大学 #理学部</code> または <code>九州大学, 理学部</code>
                    </p>
                    @error('hashtags')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 投稿ボタン -->
                <div class="flex justify-center">
                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                    >
                        投稿する
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-app-layout>
