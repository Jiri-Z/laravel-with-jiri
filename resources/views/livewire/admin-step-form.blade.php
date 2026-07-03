@use('App\Enums\StepType')
<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.steps.index', [$course, $lesson]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">{{ __('admin.back_to_steps') }}</a>

            <div class="bg-white dark:bg-gray-750 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $step ? __('admin.edit_step') : __('admin.create_step') }}</h1>

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_title') }}</label>
                            <input wire:model="title" id="title" type="text" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_type') }}</label>
                            <select wire:model.live="type" id="type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (StepType::cases() as $stepType)
                                    <option value="{{ $stepType->value }}">{{ __("enums.step_type_{$stepType->value}") }}</option>
                                @endforeach
                            </select>
                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div x-data="{ showCoding: $wire.type === 'coding' }" x-init="$watch('$wire.type', v => showCoding = v === 'coding')">
                            {{-- Reading / Quiz content --}}
                            <div x-show="!showCoding">
                                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('admin.label_content') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                        {{ __('admin.label_content_hint') }}
                                    </span>
                                </label>
                                <textarea wire:model="content" id="content" rows="10" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Coding fields --}}
                            <div x-show="showCoding" x-cloak class="space-y-4">
                                <div>
                                    <label for="prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_prompt') }}</label>
                                    <textarea wire:model="prompt" id="prompt" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('prompt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div x-data="{}" x-init="
                                    if (showCoding) {
                                        import('https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs/loader.js').then(() => {
                                            require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs' } });
                                            require(['vs/editor/editor.main'], (monaco) => {
                                                const initialEditor = monaco.editor.create($refs.initialCode, {
                                                    value: $wire.initialCode,
                                                    language: 'php',
                                                    theme: 'vs-dark',
                                                    minimap: { enabled: false },
                                                    fontSize: 14,
                                                    automaticLayout: true,
                                                });
                                                initialEditor.onDidChangeModelContent(() => {
                                                    $wire.set('initialCode', initialEditor.getValue());
                                                });

                                                const testEditor = monaco.editor.create($refs.testCode, {
                                                    value: $wire.testCode,
                                                    language: 'php',
                                                    theme: 'vs-dark',
                                                    minimap: { enabled: false },
                                                    fontSize: 14,
                                                    automaticLayout: true,
                                                });
                                                testEditor.onDidChangeModelContent(() => {
                                                    $wire.set('testCode', testEditor.getValue());
                                                });
                                            });
                                        });
                                    }
                                ">
                                    <div>
                                        <label for="initialCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_initial_code') }}</label>
                                        <div x-ref="initialCode" style="height: 200px; border: 1px solid #374151; border-radius: 0.5rem; overflow: hidden;"></div>
                                        @error('initialCode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="testCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_test_code') }}</label>
                                        <div x-ref="testCode" style="height: 150px; border: 1px solid #374151; border-radius: 0.5rem; overflow: hidden;"></div>
                                        @error('testCode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="expectedOutput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_expected_output') }}</label>
                                    <textarea wire:model="expectedOutput" id="expectedOutput" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('expectedOutput') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $step ? __('admin.submit_update', ['resource' => 'Step']) : __('admin.submit_create', ['resource' => 'Step']) }}
                            </button>
                            <a href="{{ route('admin.steps.index', [$course, $lesson]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline">{{ __('admin.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
