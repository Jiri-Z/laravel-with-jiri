<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">REPL</h1>
            <p class="text-gray-500 dark:text-gray-400 mb-8">
                {{ __('repl.subtitle') }}
            </p>

            <div
                x-data="repl({})"
                x-init="init()"
                @keydown="handleKeydown($event)"
                class="grid grid-cols-1 lg:grid-cols-2 gap-6"
            >
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">{{ __('repl.php_input') }}</h3>

                    <textarea x-ref="codeEditor" spellcheck="false" rows="20"
                        class="w-full font-mono text-sm p-3 bg-gray-900 text-blue-300 border border-gray-700 rounded-lg resize-y focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >&lt;?php

echo "Hello, World!\n";

$items = ["PHP", "WASM", "Browser"];
foreach ($items as $i) {
    echo "- $i\n";
}</textarea>

                    <div class="flex items-center gap-3 mt-4">
                        <button
                            x-on:click="run()"
                            x-bind:disabled="!phpReady || running"
                            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span x-show="!running">{{ __('repl.run') }}</span>
                            <span x-show="running">{{ __('repl.running') }}</span>
                        </button>

                        <button
                            x-on:click="resetCode()"
                            class="inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            {{ __('repl.reset') }}
                        </button>

                        <span class="text-xs text-gray-400 ml-auto">Ctrl+Enter</span>
                    </div>

                    <div x-show="status === 'loading'" class="mt-3 text-sm text-gray-500 dark:text-gray-400 animate-pulse">
                        {{ __('repl.loading_php_runtime') }}
                    </div>

                    <div x-show="status === 'ready'" class="mt-3 text-sm text-green-500 dark:text-green-400">
                        {{ __('repl.php_runtime_ready') }}
                    </div>

                    <div x-show="status === 'error'" class="mt-3 text-sm text-red-500 dark:text-red-400">
                        {{ __('repl.php_runtime_failed') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('repl.console_output') }}</h3>
                        <button
                            x-show="output !== ''"
                            x-on:click="clearOutput()"
                            class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 underline"
                        >
                            {{ __('repl.clear_output') }}
                        </button>
                    </div>

                    <div id="php-output" class="flex-1 bg-gray-900 text-green-400 border-l-4 border-indigo-500 p-4 rounded-r-lg font-mono text-sm whitespace-pre-wrap overflow-y-auto min-h-[400px] max-h-[600px]">
                        <span x-show="output === '' && status !== 'loading'" class="text-gray-500 italic">
                            {{ __('repl.output_placeholder') }}
                        </span>
                        <span x-text="output"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
