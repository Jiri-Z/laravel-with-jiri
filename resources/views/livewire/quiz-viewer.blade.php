@php $quizContent = $step->getContentAsArray(); @endphp
<div>
    <div class="mb-6">
        <p class="text-lg text-gray-900 dark:text-white mb-4">{{ $quizContent['question'] ?? '' }}</p>

        @if ($step->type === 'quiz_single')
            <div class="space-y-2">
                @foreach ($quizContent['options'] ?? [] as $index => $option)
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 {{ $submitted ? 'pointer-events-none opacity-75' : '' }}">
                        <input type="radio" name="quiz_answer" value="{{ $index }}" wire:model.live="selectedAnswer" {{ $submitted ? 'disabled' : '' }}>
                        <span>{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        @elseif ($step->type === 'quiz_multiple')
            <div class="space-y-2">
                @foreach ($quizContent['options'] ?? [] as $index => $option)
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 {{ $submitted ? 'pointer-events-none opacity-75' : '' }}">
                        <input type="checkbox" value="{{ $index }}" wire:model.live="selectedAnswers" {{ $submitted ? 'disabled' : '' }}>
                        <span>{{ $option }}</span>
                    </label>
                @endforeach
            </div>
        @elseif ($step->type === 'quiz_text')
            <div>
                <textarea wire:model="textAnswer" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" {{ $submitted ? 'disabled' : '' }}></textarea>
            </div>
        @endif
    </div>

    @if (!$submitted)
        <button wire:click="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Submit Answer
        </button>
    @else
        <div class="flex items-center gap-2 {{ $isCorrect ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            @if ($isCorrect)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">Correct!</span>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">Incorrect</span>
            @endif
        </div>
    @endif
</div>
