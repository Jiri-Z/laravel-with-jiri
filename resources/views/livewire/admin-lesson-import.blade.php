<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.lessons.index', $course) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">{{ __('admin.back_to_lessons') }}</a>

            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Lesson into "{{ $course->title }}"</h1>
            </div>

            <div class="bg-white dark:bg-gray-750 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($error)
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-700 rounded-md">
                            <p class="text-red-700 dark:text-red-300 text-sm">{{ $error }}</p>
                        </div>
                    @endif

                    @if ($parsedLesson === null)
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                            </svg>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                Upload a YAML file containing the lesson structure
                            </p>
                            <label class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
                                {{ __('admin.choose_yaml_file') }}
                                <input type="file" wire:model="yamlFile" accept=".yaml,.yml,.txt" class="hidden">
                            </label>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">.yaml, .yml, or .txt (max 50MB)</p>
                        </div>
                    @else
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Preview</h2>
                                <button wire:click="removeFile" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Remove file</button>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-4">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $parsedLesson['title'] }}</h3>
                                @if (!empty($parsedLesson['description']))
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $parsedLesson['description'] }}</p>
                                @endif
                            </div>

                            <div class="space-y-3">
                                @foreach ($parsedSteps as $index => $step)
                                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 border-l-4
                                        {{ $step['type'] === 'reading' ? 'border-blue-400' : '' }}
                                        {{ $step['type'] === 'quiz' ? 'border-amber-400' : '' }}
                                        {{ $step['type'] === 'coding' ? 'border-green-400' : '' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-200 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $index + 1 }}</span>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $step['title'] }}</span>
                                            </div>
                                            <span class="text-xs font-medium px-2 py-0.5 rounded
                                                {{ $step['type'] === 'reading' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : '' }}
                                                {{ $step['type'] === 'quiz' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300' : '' }}
                                                {{ $step['type'] === 'coding' ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : '' }}">
                                                {{ ucfirst($step['type']) }}
                                            </span>
                                        </div>
                                        @if ($step['type'] === 'quiz' && isset($step['questions']))
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ count($step['questions']) }} question(s)</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex items-center gap-4">
                                <button
                                    wire:click="import"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                >
                                    <svg wire:loading wire:target="import" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ __('admin.import_lesson_button') }}
                                </button>
                                <span wire:loading wire:target="import" class="text-sm text-gray-500 dark:text-gray-400">{{ __('admin.importing') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
