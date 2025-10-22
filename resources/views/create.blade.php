<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-gray-100">
            質問を投稿する
        </h2>
    </x-slot>

    <main class="flex justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 text-center">
                新しい質問を投稿
            </h1>

            <form method="POST" action="{{ route('questions.store') }}" class="space-y-6">
                @csrf

                <!-- タイトル -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">タイトル</label>
                    <input id="title" name="title" type="text" required
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="例：Laravelでバリデーションを実装するには？" value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 内容 -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">内容</label>
                    <textarea id="body" name="body" rows="6" required
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="質問内容を具体的に書いてください。">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 大学タグ（ローカル検索で追加） -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        大学タグ（検索して追加）
                    </label>

                    <!-- 検索ボックス -->
                    <input id="univ-search" type="text"
                        class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="大学名を入力してください..." autocomplete="off">

                    <!-- 候補リスト -->
                    <ul id="univ-results"
                        class="mt-2 hidden bg-white dark:bg-gray-700 rounded-lg shadow border border-gray-300 dark:border-gray-600 max-h-56 overflow-auto"></ul>

                    <!-- 選択済みタグ -->
                    <div id="selected-tags" class="mt-3 flex flex-wrap gap-2"></div>

                    @error('university_tag_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 投稿ボタン -->
                <div class="flex justify-center">
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        投稿する
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
    // ======== データ受け取り（null/型崩れに強く） ========
    // Controller から渡す想定: $universityTags（id と label を含む配列 or 文字列配列）
    const allUniversitiesRaw = @json($universityTags ?? []);
    const allUniversities = Array.isArray(allUniversitiesRaw)
        ? allUniversitiesRaw.map((u, i) => {
            if (typeof u === 'string') {
                // 文字列配列でも動くように正規化
                return { id: i + 1, label: u };
            }
            if (u && typeof u === 'object') {
                const id = u.id ?? u.value ?? u.tag_id ?? u.university_id ?? (u.slug ?? (u.name ?? (i + 1)));
                const label = u.label ?? u.name ?? u.slug ?? String(id);
                return { id, label };
            }
            return null;
        }).filter(Boolean)
        : [];

    const input = document.getElementById('univ-search');
    const results = document.getElementById('univ-results');
    const selected = document.getElementById('selected-tags');
    const chosen = new Set();

    // ======== 入力ごとの候補生成 ========
    input.addEventListener('input', () => {
        const q = input.value.trim().toLowerCase();
        results.innerHTML = '';
        if (!q) {
            results.classList.add('hidden');
            return;
        }

        const filtered = allUniversities
            .filter(u => (u.label ?? '').toLowerCase().includes(q))
            .slice(0, 15);

        if (!filtered.length) {
            results.classList.add('hidden');
            return;
        }

        results.classList.remove('hidden');
        filtered.forEach(row => {
            const li = document.createElement('li');
            li.className = 'px-3 py-2 hover:bg-indigo-100 dark:hover:bg-indigo-800 cursor-pointer text-sm';
            li.textContent = row.label;
            li.addEventListener('click', () => addTag(row.id, row.label));
            results.appendChild(li);
        });
    });

    // ======== タグ追加 ========
    function addTag(tagId, label) {
        if (chosen.has(tagId)) return;
        chosen.add(tagId);

        const hid = document.createElement('input');
        hid.type = 'hidden';
        hid.name = 'university_tag_ids[]';
        hid.value = tagId;

        const badge = document.createElement('span');
        badge.className =
            'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200';
        badge.textContent = '#' + label;

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'ml-1 text-indigo-600 hover:text-indigo-800';
        close.setAttribute('aria-label', 'remove');
        close.innerHTML = '&times;';
        close.addEventListener('click', () => {
            hid.remove();
            badge.remove();
            chosen.delete(tagId);
        });

        badge.appendChild(close);
        selected.appendChild(badge);
        selected.appendChild(hid);

        input.value = '';
        results.classList.add('hidden');
    }

    // ======== フォーカス外れたら候補を閉じる ========
    document.addEventListener('click', (e) => {
        if (!results.contains(e.target) && e.target !== input) {
            results.classList.add('hidden');
        }
    });
    </script>
</x-app-layout>
