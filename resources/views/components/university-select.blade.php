@props(['name' => 'university_id', 'value' => null, 'selected' => null])

<div x-data="universitySelect(@js($value), @js($selected))" class="relative">
    <input 
        type="hidden" 
        name="{{ $name }}" 
        x-ref="hiddenInput"
        :value="selectedUniversity?.id || ''"
    >
    
    <div class="relative">
        <input 
            type="text"
            x-model="query"
            @input.debounce.300ms="search()"
            @focus="open = true; if(query.length > 0) search()"
            @blur="setTimeout(() => open = false, 200)"
            @keydown.arrow-down.prevent="highlightNext()"
            @keydown.arrow-up.prevent="highlightPrev()"
            @keydown.enter.prevent=""
            @keydown.escape="closeDropdown()"
            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            placeholder="大学を検索..."
            autocomplete="off"
            role="combobox"
            aria-expanded="false"
            aria-haspopup="listbox"
            aria-label="大学を検索"
            x-bind:aria-expanded="open"
        >
        
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg x-show="loading" class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg x-show="!loading" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>
    
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg max-h-60 overflow-auto"
        role="listbox"
    >
        <template x-for="(university, index) in universities" :key="university.id">
            <div 
                @click="selectUniversity(university)"
                @mouseover="highlightedIndex = index"
                :class="{
                    'bg-indigo-50 dark:bg-indigo-900/20': index === highlightedIndex,
                    'text-indigo-900 dark:text-indigo-100': index === highlightedIndex,
                    'text-gray-900 dark:text-gray-100': index !== highlightedIndex
                }"
                class="cursor-pointer px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-between"
                role="option"
                :aria-selected="index === highlightedIndex"
            >
                <div>
                    <div x-text="university.name" class="font-medium"></div>
                    <div x-text="university.name_kana" class="text-sm text-gray-500 dark:text-gray-400"></div>
                </div>
                <span x-text="university.type" class="text-sm px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300"></span>
            </div>
        </template>
        
        <div x-show="loading" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
            検索中...
        </div>
        
        <div x-show="!loading && universities.length === 0 && query.length > 0" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
            該当する大学が見つかりません
        </div>
        
        <div x-show="!loading && universities.length === 0 && query.length === 0" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">
            大学名を入力してください
        </div>
    </div>
</div>

<script>
function universitySelect(initialValue = null, initialSelected = null) {
    return {
        open: false,
        query: initialSelected?.name || '',
        universities: [],
        selectedUniversity: initialSelected,
        highlightedIndex: -1,
        loading: false,
        
        async search() {
            if (this.query.length < 1) {
                this.universities = [];
                return;
            }
            
            this.loading = true;
            try {
                const response = await fetch(`/api/universities/search?q=${encodeURIComponent(this.query)}`);
                if (response.ok) {
                    this.universities = await response.json();
                    this.highlightedIndex = this.universities.length > 0 ? 0 : -1;
                } else {
                    console.error('Search request failed:', response.status);
                    this.universities = [];
                }
            } catch (error) {
                console.error('Search error:', error);
                this.universities = [];
            }
            this.loading = false;
        },
        
        selectUniversity(university) {
            this.selectedUniversity = university;
            this.query = university.name;
            this.universities = [];
            this.open = false;
            this.highlightedIndex = -1;
        },
        
        
        highlightNext() {
            if (this.universities.length === 0) return;
            this.highlightedIndex = Math.min(this.highlightedIndex + 1, this.universities.length - 1);
        },
        
        highlightPrev() {
            if (this.universities.length === 0) return;
            this.highlightedIndex = Math.max(this.highlightedIndex - 1, 0);
        },
        
        closeDropdown() {
            this.open = false;
            this.universities = [];
            this.highlightedIndex = -1;
        }
    }
}
</script>