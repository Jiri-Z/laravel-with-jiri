<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('courses.index') }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">{{ __('courses.back') }}</a>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $course->description }}</p>

            <div class="mb-8">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('courses.course_progress') }}</span>
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-300">{{ $courseProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-indigo-600 dark:bg-indigo-400 h-2.5 rounded-full transition-all" style="width: {{ $courseProgress }}%"></div>
                </div>
            </div>

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">{{ __('courses.lessons') }}</h2>

            <div class="space-y-4">
                @forelse ($course->lessons as $lesson)
                    <a href="{{ route('lessons.show', [$course->slug, $lesson->slug]) }}" wire:navigate class="group block bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">
                                    {{ $lesson->title }}
                                </h3>
                                @if ($lesson->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($lesson->description, 100) }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                @if ($lessonCompletion[$lesson->id])
                                    <span class="text-sm text-green-600 dark:text-green-400 font-medium">{{ __('courses.lesson_complete') }}</span>
                                @endif
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('courses.lesson_number', ['order' => $lesson->order]) }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">{{ __('courses.no_lessons') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
