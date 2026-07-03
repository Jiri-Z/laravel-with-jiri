<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    @if ($totalCompleted > 0)
                        You've completed <span class="font-semibold text-indigo-600 dark:text-indigo-300">{{ $totalCompleted }}</span> {{ Str::plural('step', $totalCompleted) }} across <span class="font-semibold text-indigo-600 dark:text-indigo-300">{{ $courses->count() }}</span> {{ Str::plural('course', $courses->count()) }}.
                    @else
                        Start learning — pick a course below.
                    @endif
                </p>
            </div>

            @if ($resumeStep)
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Continue Learning</h2>
                    <a href="{{ route('steps.show', [$resumeStep->lesson->course->slug, $resumeStep->lesson->slug, $resumeStep->id]) }}" wire:navigate class="group block bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $resumeStep->lesson->course->title }} &middot; {{ $resumeStep->lesson->title }}</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">{{ $resumeStep->title }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>
            @elseif ($courses->isNotEmpty())
                <div class="mb-8 p-5 bg-green-50 dark:bg-green-900/20 rounded-2xl border border-green-200 dark:border-green-800">
                    <p class="text-green-800 dark:text-green-300 font-medium">All courses complete! 🎉</p>
                </div>
            @endif

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Course Progress</h2>
                @if ($courses->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No courses available yet.</p>
                @else
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($courses as $course)
                            <a href="{{ route('courses.show', $course->slug) }}" wire:navigate class="group block bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors mb-2">
                                    {{ $course->title }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">
                                    {{ Str::limit($course->description, 150) }}
                                </p>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}
                                    </span>
                                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-300">{{ $progressData[$course->id] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-indigo-600 dark:bg-indigo-400 h-2 rounded-full transition-all" style="width: {{ $progressData[$course->id] }}%"></div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Laravel Trivia</h2>
                <a href="{{ route('quiz') }}" wire:navigate class="group block bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">Test Your Laravel Knowledge</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">406 questions across 18 topics — challenge yourself!</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>

            @if ($recentCompletions->isNotEmpty())
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Recent Activity</h2>
                    <div class="bg-white dark:bg-gray-750 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($recentCompletions as $completion)
                            <a href="{{ route('steps.show', [$completion->step->lesson->course->slug, $completion->step->lesson->slug, $completion->step->id]) }}" wire:navigate class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $completion->step->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $completion->step->lesson->course->title }} &middot; {{ $completion->step->lesson->title }}</p>
                                </div>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $completion->completed_at->diffForHumans() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
