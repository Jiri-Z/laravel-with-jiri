<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Courses</h1>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($courses as $course)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                <a href="{{ route('courses.show', $course->slug) }}" wire:navigate class="hover:underline">
                                    {{ $course->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                {{ Str::limit($course->description, 150) }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 col-span-full">No courses available yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
