<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ darkMode: false, mobileMenuOpen: false }"
      x-bind:class="darkMode ? 'dark' : ''"
      class="h-full bg-gray-50 dark:bg-gray-900">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>

    <body class="min-h-screen flex flex-col font-sans text-gray-900 dark:text-gray-100">

        <!-- ===== Header / Navigation ===== -->
        <header class="sticky top-0 z-50 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
            <nav class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">

                    <!-- Left: Logo -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <x-application-logo class="h-8 w-8 text-blue-600 dark:text-blue-400" />
                            <span class="font-semibold text-lg text-gray-900 dark:text-white">Q&A Board</span>
                        </a>
                    </div>

                    <!-- Center: Navigation -->
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('questions.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">質問一覧</a>
                        <a href="{{ route('questions.create') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">質問する</a>
                    </div>

                    <!-- Right: User + Notification -->
                    <div class="flex items-center space-x-4">

                        {{-- 🔔 通知アイコン --}}
                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6 6 0 10-12 0v3c0 .386-.146.735-.395 1.005L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                                    @endif
                                </button>

                                <div x-show="open" @click.away="open = false"
                                     class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-md shadow-lg py-2 ring-1 ring-black ring-opacity-5 z-50">
                                    @forelse(Auth::user()->unreadNotifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                           class="block px-4 py-2 text-sm text-gray-800 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            {{ $notification->data['message'] }}
                                        </a>
                                    @empty
                                        <p class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">通知はありません</p>
                                    @endforelse
                                </div>
                            </div>
                        @endauth

                        <!-- 🌙 ダークモード切替 -->
                        <button @click="darkMode = !darkMode" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1z" />
                            </svg>
                            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M17.293 13.293..." clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- 👤 ユーザードロップダウン -->
                        <div x-data="{ open: false }" class="relative hidden md:block">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm focus:outline-none">
                                <span>{{ Auth::user()->name ?? 'Guest' }}</span>
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">プロフィール</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">ログアウト</button>
                                </form>
                            </div>
                        </div>

                        <!-- 📱 モバイルメニュー -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                                class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }"
                                      class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }"
                                      class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- ===== モバイルメニュー展開部分 ===== -->
                <div x-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 dark:border-gray-700 mt-2 pt-2 pb-3 space-y-2">
                    <a href="{{ route('questions.index') }}" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">質問一覧</a>
                    <a href="{{ route('questions.create') }}" class="block px-3 py-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">質問する</a>

                    @auth
                        <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">プロフィール</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">ログアウト</button>
                        </form>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- ===== Main Content ===== -->
        <main class="flex-grow py-10 px-4 sm:px-6 lg:px-8">
            <div class="max-w-5xl mx-auto">
                @isset($header)
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">{{ $header }}</h1>
                @endisset
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- ===== Footer ===== -->
        <footer class="mt-auto py-6 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700">
            <p>© {{ date('Y') }} Q&A Board — Built with Laravel + Tailwind</p>
        </footer>
    </body>
</html>
