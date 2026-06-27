<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.lessons.index', $course) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">&larr; Back to lessons</a>

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Steps: {{ $lesson->title }}</h1>
                <a href="{{ route('admin.steps.create', [$course, $lesson]) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    New Step
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($steps->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No steps yet.</p>
                    @else
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Order</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Title</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Type</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($steps as $step)
                                    <tr class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-3 text-sm text-gray-900 dark:text-white">
                                            <div class="flex items-center gap-1">
                                                <button wire:click="moveUp({{ $step->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Move up">&uarr;</button>
                                                <span>{{ $step->order }}</span>
                                                <button wire:click="moveDown({{ $step->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="Move down">&darr;</button>
                                            </div>
                                        </td>
                                        <td class="py-3 text-sm text-gray-900 dark:text-white">
                                            <a href="{{ route('admin.steps.edit', [$course, $lesson, $step]) }}" wire:navigate class="hover:underline">{{ $step->title }}</a>
                                        </td>
                                        <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ $step->type }}</td>
                                        <td class="py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.steps.edit', [$course, $lesson, $step]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-400 hover:underline">Edit</a>
                                                <button wire:click="delete({{ $step->id }})" wire:confirm="Are you sure?" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
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
