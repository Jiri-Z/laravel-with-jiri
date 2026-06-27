<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('courses.index') }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">&larr; Back to courses</a>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $course->description }}</p>

            <div class="mb-8">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Course progress</span>
                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $courseProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-indigo-600 h-2.5 rounded-full transition-all" style="width: {{ $courseProgress }}%"></div>
                </div>
            </div>

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Lessons</h2>

            <div class="space-y-4">
                @forelse ($course->lessons as $lesson)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('lessons.show', [$course->slug, $lesson->slug]) }}" wire:navigate class="hover:underline">
                                        {{ $lesson->title }}
                                    </a>
                                </h3>
                                @if ($lesson->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($lesson->description, 100) }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                @if ($lessonCompletion[$lesson->id])
                                    <span class="text-sm text-green-600 dark:text-green-400 font-medium">Lesson complete</span>
                                @endif
                                <span class="text-sm text-gray-500 dark:text-gray-400">Lesson {{ $lesson->order }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No lessons available yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
