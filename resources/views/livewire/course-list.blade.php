<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">{{ __('courses.title') }}</h1>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($courses as $course)
                    <div class="group bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <a href="{{ route('courses.show', $course->slug) }}" wire:navigate>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors mb-2">
                                @if (str_starts_with($course->slug, 'cs-'))
                                    <span class="inline-block mr-1" title="{{ __('courses.czech_course') }}">🇨🇿</span>
                                @endif
                                {{ $course->title }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">
                                {{ Str::limit($course->description, 150) }}
                            </p>
                        </a>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ trans_choice('courses.lesson_count', $course->lessons_count) }}
                            </span>
                            @if (isset($enrolled[$course->id]))
                                <a href="{{ route('courses.show', $course->slug) }}" wire:navigate class="text-sm font-medium text-indigo-600 dark:text-indigo-300 hover:underline">{{ $progressData[$course->id] }}%</a>
                            @else
                                <button wire:click="enroll({{ $course->id }})" wire:loading.attr="disabled" wire:target="enroll" class="rounded-full bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 dark:bg-indigo-400 dark:hover:bg-indigo-300 transition-colors">
                                    <span wire:loading.remove wire:target="enroll">{{ __('courses.enroll') }}</span>
                                    <span wire:loading wire:target="enroll" class="inline-flex items-center gap-1">
                                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                        <span>{{ __('courses.enrolling') }}</span>
                                    </span>
                                </button>
                            @endif
                        </div>
                        @if (isset($enrolled[$course->id]))
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 dark:bg-indigo-400 h-2 rounded-full transition-all" style="width: {{ $progressData[$course->id] }}%"></div>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 col-span-full">{{ __('courses.no_courses') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
