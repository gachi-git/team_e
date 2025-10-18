<x-app-layout>
    <main class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col items-center justify-center px-6">
        <!-- Hero Section -->
        <section class="text-center max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight">
                ようこそ <span class="text-indigo-600">Q&Aプラットフォーム</span> へ
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                あなたの疑問を共有し、他のユーザーと一緒に解決していきましょう。<br>
                知識を広げるコミュニティの一員になりませんか？
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                    今すぐ登録する
                </a>
                <a href="{{ route('questions.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-indigo-600 border border-indigo-600 hover:bg-indigo-50 dark:hover:bg-gray-800 rounded-lg transition">
                    質問一覧を見る
                </a>
            </div>
        </section>

        <!-- Features Section -->
        <section class="mt-20 grid gap-8 md:grid-cols-3 max-w-6xl">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-xl transition text-center">
                <div class="text-indigo-600 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16h6M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">気軽に質問</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">
                    どんな小さな疑問でもOK。あなたの質問が、誰かの学びになります。
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-xl transition text-center">
                <div class="text-green-600 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">みんなで解決</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">
                    知識を持ち寄り、ユーザー同士で最適な回答を見つけましょう。
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow hover:shadow-xl transition text-center">
                <div class="text-yellow-500 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c1.657 0 3 1.343 3 3m0 0c0 3-3 6-3 6s-3-3-3-6m3 3a3 3 0 110-6z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">成長できる場</h3>
                <p class="text-gray-600 dark:text-gray-300 text-sm">
                    他の質問や回答を読むことで、新しい知識を得られます。
                </p>
            </div>
        </section>
    </main>
</x-app-layout>
