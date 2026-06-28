@use('App\Enums\StepType')
<div>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('admin.steps.index', [$course, $lesson]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline mb-4 inline-block">&larr; Back to steps</a>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ $step ? 'Edit Step' : 'New Step' }}</h1>

                    <form wire:submit="save" class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input wire:model="title" id="title" type="text" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select wire:model.live="type" id="type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (StepType::cases() as $stepType)
                                    <option value="{{ $stepType->value }}">{{ $stepType->name }}</option>
                                @endforeach
                            </select>
                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div x-data="{ showCoding: $wire.type === 'coding' }" x-init="$watch('$wire.type', v => showCoding = v === 'coding')">
                            {{-- Reading / Quiz content --}}
                            <div x-show="!showCoding">
                                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Content
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                        (Reading: plain text. Quiz: JSON array of questions)
                                    </span>
                                </label>
                                <textarea wire:model="content" id="content" rows="10" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Coding fields --}}
                            <div x-show="showCoding" x-cloak class="space-y-4">
                                <div>
                                    <label for="prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prompt</label>
                                    <textarea wire:model="prompt" id="prompt" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('prompt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="initialCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Initial Code</label>
                                    <div
                                        x-init="
                                            if (showCoding) {
                                                import('https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs/loader.js').then(() => {
                                                    require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs' } });
                                                    require(['vs/editor/editor.main'], (monaco) => {
                                                        const editor = monaco.editor.create($el, {
                                                            value: $wire.initialCode,
                                                            language: 'php',
                                                            theme: 'vs-dark',
                                                            minimap: { enabled: false },
                                                            fontSize: 14,
                                                            automaticLayout: true,
                                                        });
                                                        editor.onDidChangeModelContent(() => {
                                                            $wire.set('initialCode', editor.getValue());
                                                        });
                                                    });
                                                });
                                            }
                                        "
                                        style="height: 200px; border: 1px solid #374151; border-radius: 0.5rem; overflow: hidden;"
                                    ></div>
                                    @error('initialCode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="testCode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Test Code</label>
                                    <div
                                        x-init="
                                            if (showCoding) {
                                                import('https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs/loader.js').then(() => {
                                                    require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs' } });
                                                    require(['vs/editor/editor.main'], (monaco) => {
                                                        const editor = monaco.editor.create($el, {
                                                            value: $wire.testCode,
                                                            language: 'php',
                                                            theme: 'vs-dark',
                                                            minimap: { enabled: false },
                                                            fontSize: 14,
                                                            automaticLayout: true,
                                                        });
                                                        editor.onDidChangeModelContent(() => {
                                                            $wire.set('testCode', editor.getValue());
                                                        });
                                                    });
                                                });
                                            }
                                        "
                                        style="height: 150px; border: 1px solid #374151; border-radius: 0.5rem; overflow: hidden;"
                                    ></div>
                                    @error('testCode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="expectedOutput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expected Output</label>
                                    <textarea wire:model="expectedOutput" id="expectedOutput" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('expectedOutput') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $step ? 'Update Step' : 'Create Step' }}
                            </button>
                            <a href="{{ route('admin.steps.index', [$course, $lesson]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
