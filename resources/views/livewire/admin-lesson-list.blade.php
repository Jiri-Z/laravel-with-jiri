<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.courses.index') }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">&larr; Back to courses</a>

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lessons: {{ $course->title }}</h1>
                <a href="{{ route('admin.lessons.create', $course) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    New Lesson
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($lessons->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No lessons yet.</p>
                    @else
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Order</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Title</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lessons as $lesson)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-3 text-sm text-gray-900 dark:text-white">
                                            <div class="flex items-center gap-1">
                                                <button wire:click="moveUp({{ $lesson->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Move up">&uarr;</button>
                                                <span>{{ $lesson->order }}</span>
                                                <button wire:click="moveDown({{ $lesson->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Move down">&darr;</button>
                                            </div>
                                        </td>
                                        <td class="py-3 text-sm text-gray-900 dark:text-white">
                                            <a href="{{ route('admin.lessons.edit', [$course, $lesson]) }}" wire:navigate class="hover:underline">{{ $lesson->title }}</a>
                                        </td>
                                        <td class="py-3 text-sm">
                                            @if ($lesson->published)
                                                <span class="text-green-600 dark:text-green-400">Published</span>
                                            @else
                                                <span class="text-yellow-600 dark:text-yellow-400">Draft</span>
                                            @endif
                                        </td>
                                        <td class="py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.lessons.edit', [$course, $lesson]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                                <a href="{{ route('admin.steps.index', [$course, $lesson]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">Steps</a>
                                                <button wire:click="delete({{ $lesson->id }})" wire:confirm="Are you sure?" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
