@use('App\Enums\StepType')
<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('lessons.show', [$course->slug, $lesson->slug]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">&larr; Back to {{ $lesson->title }}</a>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $step->title }}</h1>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Step {{ $step->order }}</span>
                    </div>

                    <div class="prose dark:prose-invert max-w-none mb-8">
                        @if ($step->type === StepType::Reading)
                            {{ nl2br(e($step->content)) }}
                        @elseif (in_array($step->type, [StepType::QuizSingle, StepType::QuizMultiple, StepType::QuizText]))
                            <livewire:quiz-viewer :course="$course" :lesson="$lesson" :step="$step" wire:key="quiz-{{ $step->id }}" />
                        @elseif ($step->type === StepType::Coding)
                            <livewire:coding-viewer :course="$course" :lesson="$lesson" :step="$step" wire:key="coding-{{ $step->id }}" />
                        @endif
                    </div>

                    @if (!$completed)
                        <button wire:click="complete" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Mark as Complete
                        </button>
                    @else
                        <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Completed</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
