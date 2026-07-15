@php $question = $this->questions[$this->currentIndex] ?? null; @endphp

@if ($question)
    @php
        $difficultyColor = match ($question['difficulty'] ?? '') {
            'easy', 'lehká' => 'bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300',
            'medium', 'střední' => 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-700 dark:text-yellow-300',
            default => 'bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300',
        };
    @endphp
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
            <span class="px-2.5 py-0.5 text-xs font-semibold {{ $difficultyColor }} rounded-full">{{ ucfirst($question['difficulty']) }}</span>
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
                        <input type="radio" name="quiz-option-{{ $this->currentIndex }}" value="{{ $option }}"
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
