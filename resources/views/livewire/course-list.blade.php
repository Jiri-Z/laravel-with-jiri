<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Courses</h1>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($courses as $course)
                    <a href="{{ route('courses.show', $course->slug) }}" wire:navigate class="group block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors mb-2">
                            {{ $course->title }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">
                            {{ Str::limit($course->description, 150) }}
                        </p>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}
                            </span>
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $progressData[$course->id] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full transition-all" style="width: {{ $progressData[$course->id] }}%"></div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 col-span-full">No courses available yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
