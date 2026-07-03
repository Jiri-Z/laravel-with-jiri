@php $title = __('trivia.title'); @endphp
<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if ($screen === 'welcome')
                {{-- WELCOME SCREEN --}}
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

                <div class="text-center">
                    <button type="button" wire:click="start" @disabled(empty($selectedTopics))
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/40 disabled:shadow-none transition-all duration-200 text-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('trivia.start_quiz') }}
                    </button>
                </div>

            @elseif ($screen === 'quiz')
                {{-- QUIZ SCREEN --}}
                @php $question = $this->questions[$this->currentIndex] ?? null; @endphp

                @if ($question)
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('trivia.question_counter', ['current' => $this->currentIndex + 1, 'total' => count($this->questions)]) }}</span>
                            <button type="button" wire:click="resetQuiz" class="text-xs text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900/30">{{ __('trivia.quit') }}</button>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full transition-all duration-500" style="width: {{ ($this->currentIndex / count($this->questions)) * 100 }}%"></div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-750 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-4">
                        <div class="mb-4 flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-full">{{ Str::title(str_replace('-', ' ', $question['topic'])) }}</span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold {{ $question['difficulty'] === 'easy' ? 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300' : ($question['difficulty'] === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300' : 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300') }} rounded-full">{{ ucfirst($question['difficulty']) }}</span>
                            <span class="px-2.5 py-0.5 text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">{{ $question['type'] === 'single' ? 'Single Answer' : ($question['type'] === 'multiple' ? 'Multiple Answers' : 'Type Answer') }}</span>
                        </div>

                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 leading-relaxed">{{ $question['question'] }}</h2>

                        @if ($question['type'] === 'multiple')
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4"><em>{{ __('trivia.select_all_that_apply') }}</em></p>
                        @endif

                        @if ($question['type'] === 'single')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $optIndex => $option)
                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all
                                        {{ $submitted && $option === $question['answer'] ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : '' }}
                                        {{ $submitted && ($this->userAnswers[$this->currentIndex] ?? null) === $option && $option !== $question['answer'] ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : '' }}
                                        {{ !$submitted ? 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20' : 'opacity-70 pointer-events-none' }}
                                        {{ !$submitted && ($this->userAnswers[$this->currentIndex] ?? null) === $option ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                        <input type="radio" name="quiz-option" value="{{ $option }}"
                                            wire:model.live="userAnswers.{{ $this->currentIndex }}"
                                            {{ $submitted ? 'disabled' : '' }}
                                            class="mt-0.5 w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                        <span class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif ($question['type'] === 'multiple')
                            <div class="space-y-2">
                                @foreach ($question['options'] ?? [] as $optIndex => $option)
                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all
                                        {{ $submitted && in_array($option, (array) json_decode($question['answer'] ?? '[]')) ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : '' }}
                                        {{ $submitted && is_array($this->userAnswers[$this->currentIndex] ?? null) && in_array($option, $this->userAnswers[$this->currentIndex]) && !in_array($option, (array) json_decode($question['answer'] ?? '[]')) ? 'border-red-500 bg-red-50 dark:bg-red-900/20' : '' }}
                                        {{ !$submitted ? 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/20' : 'opacity-70 pointer-events-none' }}">
                                        <input type="checkbox" value="{{ $option }}"
                                            wire:model.live="userAnswers.{{ $this->currentIndex }}"
                                            {{ $submitted ? 'disabled' : '' }}
                                            class="mt-0.5 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <span class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif ($question['type'] === 'text')
                            <div>
                                <input type="text"
                                    wire:model.live="userAnswers.{{ $this->currentIndex }}"
                                    {{ $submitted ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 text-lg border-2 rounded-xl focus:ring-2 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-gray-500
                                        {{ $submitted ? ($this->questions[$this->currentIndex]['answer'] === ($this->userAnswers[$this->currentIndex] ?? '') ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-red-500 bg-red-50 dark:bg-red-900/20') : 'border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-200' }}"
                                    placeholder="{{ __('trivia.text_placeholder') }}" autocomplete="off">
                                <p class="mt-2 text-xs text-gray-400">{{ __('trivia.text_hint') }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($submitted)
                        @php
                            $currentAnswer = $this->userAnswers[$this->currentIndex] ?? null;
                            $isCorrect = $this->checkAnswer($question, $currentAnswer);
                        @endphp
                        <div class="mb-4">
                            <div class="flex items-start gap-3 p-4 rounded-xl {{ $isCorrect ? 'bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }}">
                                <div class="shrink-0 w-8 h-8 flex items-center justify-center rounded-full {{ $isCorrect ? 'bg-emerald-500' : 'bg-red-500' }} text-white font-bold text-sm">
                                    {{ $isCorrect ? '✓' : '✗' }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold {{ $isCorrect ? 'text-emerald-800 dark:text-emerald-300' : 'text-red-800 dark:text-red-300' }}">
                                        {{ $isCorrect ? __('trivia.correct') : __('trivia.not_quite') }}
                                    </div>
                                    @if (!$isCorrect && $currentAnswer !== null)
                                        <div class="text-sm mt-1">
                                            <span class="text-gray-600 dark:text-gray-400">{{ __('trivia.correct_answer') }} </span>
                                            <span class="font-medium text-emerald-700 dark:text-emerald-300">{{ $question['answer'] }}</span>
                                        </div>
                                    @endif
                                    <div class="text-sm mt-2 leading-relaxed {{ $isCorrect ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-700 dark:text-red-300' }}">
                                        {{ $question['explanation'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3">
                        @if (!$submitted)
                            <button type="button" wire:click="submit"
                                @disabled(($this->userAnswers[$this->currentIndex] ?? '') === '' && ($question['type'] ?? '') !== 'multiple')
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl transition-all duration-200">
                                {{ __('trivia.check_answer') }}
                            </button>
                        @else
                            <button type="button" wire:click="nextQuestion"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200">
                                @if ($this->currentIndex >= count($this->questions) - 1)
                                    {{ __('trivia.see_results') }}
                                @else
                                    {{ __('trivia.next') }}
                                @endif
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endif
                    </div>
                @endif

            @elseif ($screen === 'results')
                {{-- RESULTS SCREEN --}}
                @php
                    $attempt = \App\Models\TriviaAttempt::find($this->attemptId);
                @endphp

                @if ($attempt)
                    @php
                        $pct = $attempt->total > 0 ? round(($attempt->score / $attempt->total) * 100) : 0;
                        $elapsed = $attempt->completed_at ? $attempt->created_at->diffInSeconds($attempt->completed_at) : 0;
                        $minutes = intdiv($elapsed, 60);
                        $seconds = $elapsed % 60;

                        $grade = match(true) {
                            $pct >= 95 => ['label' => __('trivia.grade_artisan_master'), 'desc' => __('trivia.grade_description_outstanding'), 'color' => 'emerald'],
                            $pct >= 85 => ['label' => __('trivia.grade_senior_dev'), 'desc' => __('trivia.grade_description_great'), 'color' => 'emerald'],
                            $pct >= 70 => ['label' => __('trivia.grade_mid_level'), 'desc' => __('trivia.grade_description_good'), 'color' => 'blue'],
                            $pct >= 55 => ['label' => __('trivia.grade_junior_dev'), 'desc' => __('trivia.grade_description_basics'), 'color' => 'yellow'],
                            $pct >= 40 => ['label' => __('trivia.grade_apprentice'), 'desc' => __('trivia.grade_description_keep_studying'), 'color' => 'orange'],
                            default => ['label' => __('trivia.grade_fresh_start'), 'desc' => __('trivia.grade_description_start'), 'color' => 'red'],
                        };

                        $topicBreakdown = collect($attempt->answers)->groupBy('topic')->map(function ($answers, $topic) {
                            $correct = $answers->where('is_correct', true)->count();
                            $total = $answers->count();
                            return [
                                'label' => Str::title(str_replace('-', ' ', $topic)),
                                'correct' => $correct,
                                'total' => $total,
                                'pct' => $total > 0 ? round(($correct / $total) * 100) : 0,
                            ];
                        })->sortKeys();
                    @endphp

                    <div class="mb-6 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('trivia.score', ['score' => $attempt->score, 'total' => $attempt->total]) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mb-8">
                        <div class="h-1.5 rounded-full transition-all duration-1000 bg-{{ $grade['color'] }}-500" style="width: 100%"></div>
                    </div>

                    <div class="bg-white dark:bg-gray-750 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-6">
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-40 h-40 rounded-full border-8 border-{{ $grade['color'] }}-400">
                                <div>
                                    <div class="text-4xl font-bold text-{{ $grade['color'] }}-600 dark:text-{{ $grade['color'] }}-400">{{ $pct }}</div>
                                    <div class="text-sm text-{{ $grade['color'] }}-500">%</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="inline-block px-4 py-1.5 rounded-full text-lg font-bold bg-{{ $grade['color'] }}-100 dark:bg-{{ $grade['color'] }}-900/30 text-{{ $grade['color'] }}-800 dark:text-{{ $grade['color'] }}-300">{{ $grade['label'] }}</span>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">{{ $grade['desc'] }}</p>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $attempt->score }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ __('trivia.correct_badge') }}</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <div class="text-2xl font-bold text-red-500 dark:text-red-400">{{ $attempt->total - $attempt->score }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ __('trivia.incorrect_badge') }}</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                                <div class="text-2xl font-bold text-gray-700 dark:text-gray-300">{{ $minutes }}:{{ str_pad((string) $seconds, 2, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ __('trivia.time') }}</div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('trivia.topic_breakdown') }}</h3>
                            <div class="space-y-3">
                                @foreach ($topicBreakdown as $topic)
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $topic['label'] }}</span>
                                            <span class="text-gray-500 dark:text-gray-400">{{ $topic['correct'] }}/{{ $topic['total'] }} ({{ $topic['pct'] }}%)</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full transition-all duration-1000 {{ $topic['pct'] >= 70 ? 'bg-emerald-500' : ($topic['pct'] >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ $topic['pct'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <button type="button" x-on:click="document.querySelector('#review-section').classList.toggle('hidden')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-750 border border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 text-gray-700 dark:text-gray-300 font-medium rounded-xl transition-all duration-200 text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            {{ __('trivia.review_all') }}
                        </button>
                    </div>

                    <div id="review-section" class="mb-8 space-y-3 hidden">
                        @foreach ($attempt->answers as $i => $answer)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden" x-data="{ open: false }">
                                <button type="button" @click="open = !open" class="w-full flex items-start gap-3 p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <div class="shrink-0 w-7 h-7 flex items-center justify-center rounded-full {{ $answer['is_correct'] ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700' : 'bg-red-100 dark:bg-red-900/40 text-red-700' }} text-xs font-bold">
                                        {{ $answer['is_correct'] ? '✓' : '✗' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200 leading-relaxed">{{ $answer['question'] }}</div>
                                        <div class="text-xs text-gray-400 mt-1">{{ Str::title(str_replace('-', ' ', $answer['topic'])) }} · {{ $answer['difficulty'] }}</div>
                                    </div>
                                    <svg class="shrink-0 w-5 h-5 text-gray-400 mt-0.5 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open" x-cloak class="border-t border-gray-100 dark:border-gray-700">
                                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 space-y-2 text-sm">
                                        <div>
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('trivia.your_answer') }}</span>
                                            <span class="{{ $answer['is_correct'] ? 'text-emerald-700 dark:text-emerald-300' : 'text-red-600 dark:text-red-400' }} font-medium ml-1">
                                                {{ is_array($answer['user_answer']) ? implode(', ', $answer['user_answer']) : ($answer['user_answer'] ?: __('trivia.no_answer')) }}
                                            </span>
                                        </div>
                                        @if (!$answer['is_correct'])
                                            <div>
                                                <span class="text-gray-500 dark:text-gray-400">{{ __('trivia.correct_answer_label') }}</span>
                                                <span class="text-emerald-700 dark:text-emerald-300 font-medium ml-1">{{ $answer['correct_answer'] }}</span>
                                            </div>
                                        @endif
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('trivia.explanation') }}</span>
                                            <p class="text-gray-700 dark:text-gray-300 mt-1 leading-relaxed">{{ $answer['explanation'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-center gap-3 pb-8">
                        <button type="button" wire:click="resetQuiz"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/40 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            {{ __('trivia.try_again') }}
                        </button>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
