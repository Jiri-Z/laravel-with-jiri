<div class="text-center mb-10">
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-3">{{ $title }}</h1>
    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
        {{ __('trivia.welcome_description') }}
        {{ __('trivia.welcome_draw_info') }}
    </p>
</div>

<div class="bg-white dark:bg-gray-750 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('trivia.select_topics') }}</h2>
        <div class="flex gap-2">
            <button type="button" wire:click="$set('selectedTopics', {{ json_encode($this->allTopics->toArray()) }})" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 transition-colors px-2 py-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/30">
                {{ __('trivia.select_all') }}
            </button>
            <span class="text-gray-300 dark:text-gray-600">|</span>
            <button type="button" wire:click="$set('selectedTopics', [])" class="text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 transition-colors px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                {{ __('trivia.deselect_all') }}
            </button>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
        @foreach ($this->topicQuestionCounts as $topicData)
            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-indigo-300 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 cursor-pointer transition-all group">
                <input type="checkbox" value="{{ $topicData->topic }}" wire:model.live="selectedTopics" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <div class="flex-1">
                    <div class="font-medium text-gray-800 dark:text-gray-200 group-hover:text-indigo-700 dark:group-hover:text-indigo-300 transition-colors">{{ Str::title(str_replace('-', ' ', $topicData->topic)) }}</div>
                    <div class="text-xs text-gray-400">{{ $topicData->count }} {{ __('trivia.questions_available', ['count' => $topicData->count, 'topics' => $topicData->topic]) }}</div>
                </div>
            </label>
        @endforeach
    </div>
    <p class="text-sm text-gray-400 dark:text-gray-500 text-center">
        {{ __('trivia.questions_available', [
            'count' => $this->topicQuestionCounts->sum('count'),
            'topics' => $this->topicQuestionCounts->count(),
        ]) }}
    </p>
</div>

<div class="bg-white dark:bg-gray-750 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-6">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
        {{ __('trivia.question_count_label') }}: <span class="font-bold text-indigo-600 dark:text-indigo-400 text-lg">{{ $this->questionCount }}</span>
    </label>
    <input type="range" wire:model.live="questionCount"
        min="1" max="{{ max($this->availableQuestionCount(), 1) }}"
        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 accent-indigo-600">
    <div class="flex justify-between text-xs text-gray-400 mt-1">
        <span>1</span>
        <span>{{ $this->availableQuestionCount() }} {{ __('trivia.questions_available', ['count' => $this->availableQuestionCount(), 'topics' => count($this->selectedTopics)]) }}</span>
    </div>
</div>

<div class="text-center">
    <button type="button" wire:click="start" @disabled(empty($selectedTopics))
        class="inline-flex items-center gap-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/40 disabled:shadow-none transition-all duration-200 text-lg">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ __('trivia.start_quiz') }}
    </button>
</div>
