<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
            ダッシュボード
        </h2>
    </x-slot>

    <main class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12 px-6">
        <div class="max-w-6xl mx-auto">
            <!-- 挨拶セクション -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-8 mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                    ようこそ、{{ Auth::user()->name }} さん！
                </h1>
                <p class="text-gray-600 dark:text-gray-300 text-lg">
                    ダッシュボードへようこそ 🎉
                </p>
            </div>

            <!-- 機能カード群 -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- 質問一覧カード -->
                <a href="{{ route('questions.index') }}"
                    class="block bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            質問一覧を見る
                        </h3>
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">
                            Q&A
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        他のユーザーの質問を閲覧し、気になるトピックをチェックしましょう。
                    </p>
                </a>

                <!-- 質問投稿カード -->
                <a href="{{ route('questions.create') }}"
                    class="block bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            質問を投稿する
                        </h3>
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                            新規投稿
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        疑問点や相談したいことを投稿して、みんなと共有しましょう。
                    </p>
                </a>

                <!-- プロフィールカード -->
                <a href="{{ route('profile.edit') }}"
                    class="block bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-xl transition p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            プロフィール設定
                        </h3>
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                            アカウント
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">
                        表示名やメールアドレスを変更できます。
                    </p>
                </a>
            </div>
        </div>
    </main>
</x-app-layout>
