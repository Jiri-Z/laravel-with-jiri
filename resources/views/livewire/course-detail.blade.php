<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('courses.index') }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">&larr; Back to courses</a>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $course->title }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">{{ $course->description }}</p>

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Lessons</h2>

            <div class="space-y-4">
                @forelse ($course->lessons as $lesson)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="#" wire:navigate class="hover:underline">
                                        {{ $lesson->title }}
                                    </a>
                                </h3>
                                @if ($lesson->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($lesson->description, 100) }}</p>
                                @endif
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Lesson {{ $lesson->order }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No lessons available yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
