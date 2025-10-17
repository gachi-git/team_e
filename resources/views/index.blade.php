<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">質問一覧</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4">
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('questions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
                    質問する
                </a>
            </div>

            @if($questions->count())
                <ul class="divide-y bg-white rounded shadow">
                    @foreach ($questions as $q)
                        <li class="p-4">
                            <div class="font-semibold">{{ $q->title }}</div>
                            <div class="text-sm text-gray-600 mt-1">
                                {{ \Illuminate\Support\Str::limit($q->body, 120) }}
                            </div>
                            <div class="text-xs text-gray-400 mt-2">
                                {{ $q->created_at->format('Y-m-d H:i') }}
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            @else
                <p class="text-gray-600">まだ質問がありません。右上の「質問する」から投稿しましょう。</p>
            @endif
        </div>
    </div>
</x-app-layout>
