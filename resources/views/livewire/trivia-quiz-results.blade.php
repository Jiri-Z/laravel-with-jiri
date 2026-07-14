@php
    $attempt = $this->attempt;
@endphp

@if ($attempt)
    @php
        $pct = $attempt->total > 0 ? round(($attempt->score / $attempt->total) * 100) : 0;
        $elapsed = $attempt->completed_at ? $attempt->created_at->diffInSeconds($attempt->completed_at) : 0;
        $minutes = intdiv($elapsed, 60);
        $seconds = $elapsed % 60;

        $grade = match(true) {
            $pct >= 85 => [
                'label' => $pct >= 95 ? __('trivia.grade_artisan_master') : __('trivia.grade_senior_dev'),
                'desc' => $pct >= 95 ? __('trivia.grade_description_outstanding') : __('trivia.grade_description_great'),
                'classes' => [
                    'bg' => 'bg-emerald-500',
                    'border' => 'border-emerald-400',
                    'text' => 'text-emerald-600 dark:text-emerald-400',
                    'text_pct' => 'text-emerald-500',
                    'badge' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300',
                ],
            ],
            $pct >= 70 => [
                'label' => __('trivia.grade_mid_level'),
                'desc' => __('trivia.grade_description_good'),
                'classes' => [
                    'bg' => 'bg-blue-500',
                    'border' => 'border-blue-400',
                    'text' => 'text-blue-600 dark:text-blue-400',
                    'text_pct' => 'text-blue-500',
                    'badge' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                ],
            ],
            $pct >= 55 => [
                'label' => __('trivia.grade_junior_dev'),
                'desc' => __('trivia.grade_description_basics'),
                'classes' => [
                    'bg' => 'bg-yellow-500',
                    'border' => 'border-yellow-400',
                    'text' => 'text-yellow-600 dark:text-yellow-400',
                    'text_pct' => 'text-yellow-500',
                    'badge' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                ],
            ],
            $pct >= 40 => [
                'label' => __('trivia.grade_apprentice'),
                'desc' => __('trivia.grade_description_keep_studying'),
                'classes' => [
                    'bg' => 'bg-orange-500',
                    'border' => 'border-orange-400',
                    'text' => 'text-orange-600 dark:text-orange-400',
                    'text_pct' => 'text-orange-500',
                    'badge' => 'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300',
                ],
            ],
            default => [
                'label' => __('trivia.grade_fresh_start'),
                'desc' => __('trivia.grade_description_start'),
                'classes' => [
                    'bg' => 'bg-red-500',
                    'border' => 'border-red-400',
                    'text' => 'text-red-600 dark:text-red-400',
                    'text_pct' => 'text-red-500',
                    'badge' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                ],
            ],
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
        <div class="h-1.5 rounded-full transition-all duration-1000 {{ $grade['classes']['bg'] }}" style="width: 100%"></div>
    </div>

    <div class="bg-white dark:bg-gray-750 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-6">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-40 h-40 rounded-full border-8 {{ $grade['classes']['border'] }}">
                <div>
                    <div class="text-4xl font-bold {{ $grade['classes']['text'] }}">{{ $pct }}</div>
                    <div class="text-sm {{ $grade['classes']['text_pct'] }}">%</div>
                </div>
            </div>
            <div class="mt-4">
                <span class="inline-block px-4 py-1.5 rounded-full text-lg font-bold {{ $grade['classes']['badge'] }}">{{ $grade['label'] }}</span>
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
