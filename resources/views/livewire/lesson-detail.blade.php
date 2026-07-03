<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('courses.show', $course->slug) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">{{ __('lessons.back', ['course' => $course->title]) }}</a>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $lesson->title }}</h1>
            @if ($lesson->description)
                <p class="text-gray-600 dark:text-gray-400 mb-8">{{ $lesson->description }}</p>
            @endif

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('lessons.steps') }}</h2>

            <div class="space-y-4">
                @forelse ($lesson->steps as $step)
                    @php $locked = $stepLocked[$step->id] ?? false; @endphp
                    <a href="{{ $locked ? '#' : route('steps.show', [$course->slug, $lesson->slug, $step->id]) }}" @if(!$locked) wire:navigate @endif class="group block bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 {{ $locked ? 'opacity-60 cursor-not-allowed' : 'hover:shadow-md hover:-translate-y-1' }} transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                @if ($locked)
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                @elseif ($stepCompletion[$step->id])
                                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-800 flex items-center justify-center text-sm font-medium text-indigo-600 dark:text-indigo-200">
                                        {{ $step->order }}
                                    </span>
                                @endif
                                <div>
                                    <h3 class="text-lg font-semibold {{ $locked ? 'text-gray-400' : 'text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300' }} transition-colors">
                                        {{ $step->title }}
                                    </h3>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __("enums.step_type_{$step->type->value}") }}
                                        @if ($locked)
                                            &middot; <span class="text-gray-400">{{ __('lessons.locked') }}</span>
                                        @elseif ($stepCompletion[$step->id])
                                            &middot; <span class="text-green-600 dark:text-green-400 font-medium">{{ __('lessons.completed') }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">{{ __('lessons.no_steps') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
