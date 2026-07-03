<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.lessons.index', $course) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">{{ __('admin.back_to_lessons') }}</a>

            <div class="bg-white dark:bg-gray-750 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $lesson ? __('admin.edit_lesson') : __('admin.create_lesson') }}</h1>

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_title') }}</label>
                            <input wire:model="title" id="title" type="text" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_slug') }}</label>
                            <input wire:model="slug" id="slug" type="text" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_description') }}</label>
                            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <input wire:model="published" id="published" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                                <label for="published" class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_published') }}</label>
                            </div>

                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_order') }}</label>
                                <input wire:model="order" id="order" type="number" min="0" class="mt-1 block w-24 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $lesson ? __('admin.submit_update', ['resource' => 'Lesson']) : __('admin.submit_create', ['resource' => 'Lesson']) }}
                            </button>
                            <a href="{{ route('admin.lessons.index', $course) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline">{{ __('admin.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
