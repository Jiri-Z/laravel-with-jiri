<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.lessons.index', $course) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">{{ __('admin.back_to_lessons') }}</a>

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('admin.steps_title', ['lesson' => $lesson->title]) }}</h1>
                <a href="{{ route('admin.steps.create', [$course, $lesson]) }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('admin.new_step') }}
                </a>
            </div>

            <div class="mb-4">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('admin.search_steps') }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                >
            </div>

            <div class="bg-white dark:bg-gray-750 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($steps->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">
                            {{ $search ? __('admin.no_steps_found') : __('admin.no_steps_yet') }}
                        </p>
                    @else
                        <table class="w-full text-left">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.th_order') }}</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.th_title') }}</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.th_type') }}</th>
                                    <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('admin.th_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody wire:loading.class="opacity-50">
                                @foreach ($steps as $step)
                                    <tr wire:key="step-{{ $step->id }}" class="border-b border-gray-100 dark:border-gray-700">
                                        <td class="py-3 text-sm text-gray-900 dark:text-white">
                                            <div class="flex items-center gap-1">
                                                <button wire:click="moveUp({{ $step->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="{{ __('admin.move_up') }}">&uarr;</button>
                                                <span>{{ $step->order }}</span>
                                                <button wire:click="moveDown({{ $step->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" title="{{ __('admin.move_down') }}">&darr;</button>
                                            </div>
                                        </td>
                                        <td class="py-3 text-sm text-gray-900 dark:text-white">
                                            <a href="{{ route('admin.steps.edit', [$course, $lesson, $step]) }}" wire:navigate class="hover:underline">{{ $step->title }}</a>
                                        </td>
                                        <td class="py-3 text-sm text-gray-500 dark:text-gray-400">{{ __("enums.step_type_{$step->type->value}") }}</td>
                                        <td class="py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.steps.edit', [$course, $lesson, $step]) }}" wire:navigate class="text-indigo-600 dark:text-indigo-300 hover:underline">{{ __('admin.edit') }}</a>
                                                <button wire:click="delete({{ $step->id }})" wire:confirm="{{ __('admin.delete_confirm') }}" class="text-red-600 dark:text-red-400 hover:underline">{{ __('admin.delete') }}</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div wire:loading class="flex justify-center py-4">
                            <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <div class="mt-4">
                            {{ $steps->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
