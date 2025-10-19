<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
            質問一覧
        </h2>
    </x-slot>

    <main class="py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto">
            <!-- ヘッダー部分 -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    みんなの質問
                </h1>
                <a href="{{ route('questions.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    質問を投稿
                </a>
            </div>

            <form action="{{ route('questions.index') }}" method="GET" class="mb-6 flex gap-2">
              <input
                type="text"
                name="keyword"
                value="{{ $keyword ?? '' }}"
                placeholder="キーワードで検索してみよう"
                class="border border-gray-300 rounded-lg p-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 text-gray-800 bg-white placeholder-gray-400"


                >
                <button
                 type="submit"
                 class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                >
                 検索
                </button>
            </form>


            <!-- 質問一覧 -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($questions as $question)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition p-6 flex flex-col justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                <a href="{{ route('questions.show', $question) }}"
                                   class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                    {{ $question->title }}
                                </a>
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4">
                                {{ Str::limit(strip_tags($question->body), 120) }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mt-2">
                            <span>投稿者: {{ $question->user->name ?? '不明' }}</span>
                            <span>{{ $question->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-2xl shadow">
                        <p class="text-gray-600 dark:text-gray-300 text-lg mb-4">
                            まだ質問がありません。
                        </p>
                        <a href="{{ route('questions.create') }}"
                            class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition">
                            最初の質問を投稿する
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- ページネーション -->
            <div class="mt-10">
                {{ $questions->links() }}
            </div>
        </div>
    </main>
</x-app-layout>
