@php $quizContent = $step->getContentAsArray(); @endphp
<div>
    <div class="space-y-8 mb-6">
        @foreach ($quizContent as $index => $question)
            <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                <p class="text-lg text-gray-900 dark:text-white mb-4">{{ $question['question'] ?? '' }}</p>

                @if (($question['type'] ?? 'single') === 'single')
                    <div class="space-y-2">
                        @foreach ($question['options'] ?? [] as $optIndex => $option)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 {{ $submitted ? 'pointer-events-none opacity-75' : '' }} {{ !$submitted && isset($answers[$index]) && $answers[$index] === $optIndex ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 ring-1 ring-indigo-500' : '' }}">
                                <input type="radio" name="q{{ $index }}" value="{{ $optIndex }}" wire:model="answers.{{ $index }}" {{ $submitted ? 'disabled' : '' }}>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif (($question['type'] ?? 'single') === 'multiple')
                    <div class="space-y-2">
                        @foreach ($question['options'] ?? [] as $optIndex => $option)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-750 {{ $submitted ? 'pointer-events-none opacity-75' : '' }} {{ !$submitted && isset($answers[$index]) && is_array($answers[$index]) && in_array($optIndex, $answers[$index]) ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 ring-1 ring-indigo-500' : '' }}">
                                <input type="checkbox" value="{{ $optIndex }}" wire:model="answers.{{ $index }}" {{ $submitted ? 'disabled' : '' }}>
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif (($question['type'] ?? 'single') === 'text')
                    <div>
                        <textarea wire:model="answers.{{ $index }}" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500" {{ $submitted ? 'disabled' : '' }}></textarea>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if (!$submitted)
        <button wire:click="submit" wire:loading.attr="disabled" wire:target="submit" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-full font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            <span wire:loading.remove wire:target="submit">{{ __('steps.quiz_submit') }}</span>
            <span wire:loading wire:target="submit" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                {{ __('steps.quiz_submitting') }}
            </span>
        </button>
    @else
        <div class="flex items-center gap-2 {{ $isCorrect ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
            @if ($isCorrect)
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ __('steps.quiz_all_correct') }}</span>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">{{ __('steps.quiz_some_incorrect') }}</span>
            @endif
        </div>
    @endif
</div>
