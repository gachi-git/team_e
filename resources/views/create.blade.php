<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">質問する</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4">
            <form method="POST" action="{{ route('questions.store') }}" class="bg-white p-6 rounded shadow">
                @csrf

                <div>
                    <label class="block font-medium mb-1">タイトル</label>
                    <input name="title" value="{{ old('title') }}" class="w-full border rounded p-2" />
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block font-medium mb-1">内容</label>
                    <textarea name="body" rows="6" class="w-full border rounded p-2">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">投稿する</button>
                    <a href="{{ route('questions.index') }}" class="px-4 py-2 bg-gray-200 rounded">一覧へ戻る</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
