<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
            質問の編集
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-6">
        <form action="{{ route('questions.update', $question->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">タイトル</label>
                <input type="text" name="title" id="title" value="{{ old('title', $question->title) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-200">本文</label>
                <textarea name="body" id="body" rows="6"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100">{{ old('body', $question->body) }}</textarea>
                @error('body')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">更新</button>
                <a href="{{ route('questions.show', $question->id) }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 transition">キャンセル</a>
            </div>
        </form>
    </div>
</x-app-layout>
