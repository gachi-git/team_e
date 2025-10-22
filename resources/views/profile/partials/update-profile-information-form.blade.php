<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- University（検索コンボボックス・×ボタンなし） --}}
        @php
            $universityOptions = collect();

            if (isset($universities) && $universities instanceof \Illuminate\Support\Collection && $universities->count()) {
                $universityOptions = $universities->map(fn($u) => ['id' => $u->id, 'name' => $u->name ?? $u->label ?? '']);
            } elseif (isset($univTags) && $univTags instanceof \Illuminate\Support\Collection && $univTags->count()) {
                $universityOptions = $univTags->map(fn($t) => ['id' => $t->id, 'name' => $t->label ?? $t->name ?? '']);
            }

            if ($universityOptions->isEmpty()) {
                try {
                    if (class_exists(\App\Models\University::class)) {
                        $universityOptions = \App\Models\University::orderBy('name')->get(['id','name'])
                            ->map(fn($u) => ['id' => $u->id, 'name' => $u->name ?? '']);
                    }
                    if ($universityOptions->isEmpty() && class_exists(\App\Models\Tag::class)) {
                        $universityOptions = \App\Models\Tag::where('type','university')->orderBy('label')->get(['id','label'])
                            ->map(fn($t) => ['id' => $t->id, 'name' => $t->label ?? '']);
                    }
                } catch (\Throwable $e) {
                    $universityOptions = collect();
                }
            }

            $currentUniversityId = old('university_id', $user->university_id ?? null);
        @endphp

        <div
            x-data="univSelect({
                options: @js($universityOptions->values()),
                initialId: @js((string)($currentUniversityId ?? '')),
            })"
            x-on:click.outside="close()"
            class="relative"
        >
            <x-input-label for="university_id_input" value="大学" />

            {{-- 送信用の hidden --}}
            <input type="hidden" name="university_id" x-model="selectedId" />

            {{-- 入力欄 --}}
            <div class="mt-1 relative">
                <input
                    id="university_id_input"
                    type="text"
                    x-model="query"
                    x-on:focus="open = true"
                    x-on:keydown.arrow-down.prevent="highlightNext()"
                    x-on:keydown.arrow-up.prevent="highlightPrev()"
                    x-on:keydown.enter.prevent="confirmHighlight()"
                    x-on:keydown.escape.prevent="close()"
                    autocomplete="off"
                    placeholder="大学名を入力して検索"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800
                           text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>

            {{-- ドロップダウン --}}
            <div x-show="open" x-transition x-cloak
                 class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md border border-gray-200 dark:border-gray-700
                        bg-white dark:bg-gray-800 shadow-lg">
                <template x-if="filtered.length === 0">
                    <div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">該当する大学がありません</div>
                </template>

                <ul role="listbox" aria-label="universities">
                    <template x-for="(opt, idx) in filtered" :key="opt.id">
                        <li role="option"
                            :aria-selected="idx === highlighted"
                            x-on:mouseenter="highlighted = idx"
                            x-on:click="select(opt)"
                            class="cursor-pointer px-3 py-2 text-sm"
                            :class="idx === highlighted
                                ? 'bg-indigo-600 text-white'
                                : 'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700'">
                            <span x-text="opt.name"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('university_id')" />

        </div>

        {{-- Save --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

@once
    <style>[x-cloak]{display:none !important;}</style>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('univSelect', ({ options, initialId }) => ({
                open: false,
                options: Array.isArray(options) ? options : [],
                query: '',
                highlighted: 0,
                selectedId: initialId || '',
                get selected() {
                    return this.options.find(o => String(o.id) === String(this.selectedId)) || null;
                },
                get filtered() {
                    const q = (this.query || '').toLowerCase().trim();
                    const base = q
                        ? this.options.filter(o => ((o.name || '').toLowerCase().includes(q)))
                        : this.options;
                    return base.slice().sort((a,b) => {
                        const an = (a.name||'').toLowerCase(), bn = (b.name||'').toLowerCase();
                        const as = q && an.startsWith(q) ? 0 : 1;
                        const bs = q && bn.startsWith(q) ? 0 : 1;
                        return (as - bs) || an.localeCompare(bn, 'ja');
                    });
                },
                openList() { this.open = true; },
                close() { this.open = false; this.highlighted = 0; },
                highlightNext() {
                    if (!this.open) this.open = true;
                    this.highlighted = Math.min(this.highlighted + 1, Math.max(this.filtered.length - 1, 0));
                },
                highlightPrev() {
                    if (!this.open) this.open = true;
                    this.highlighted = Math.max(this.highlighted - 1, 0);
                },
                confirmHighlight() {
                    if (this.filtered[this.highlighted]) this.select(this.filtered[this.highlighted]);
                },
                select(opt) {
                    this.selectedId = String(opt.id);
                    this.query = opt.name || '';
                    this.close();
                },
                init() {
                    if (this.selected) this.query = this.selected.name || '';
                },
            }));
        });
    </script>
@endonce
