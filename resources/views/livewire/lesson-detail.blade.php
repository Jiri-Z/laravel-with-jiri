<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('courses.show', $course->slug) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">&larr; Back to {{ $course->title }}</a>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $lesson->title }}</h1>
            @if ($lesson->description)
                <p class="text-gray-600 dark:text-gray-400 mb-8">{{ $lesson->description }}</p>
            @endif

            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Steps</h2>

            <div class="space-y-4">
                @forelse ($lesson->steps as $step)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-sm font-medium text-indigo-600 dark:text-indigo-300">
                                    {{ $step->order }}
                                </span>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        <a href="#" wire:navigate class="hover:underline">
                                            {{ $step->title }}
                                        </a>
                                    </h3>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ str_replace('_', ' ', ucfirst($step->type)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400">No steps available yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
