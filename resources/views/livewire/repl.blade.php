<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">REPL</h1>
                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    Write PHP code and run it in the browser.
                </p>
            </div>

            <div
                x-data="repl({})"
                x-init="init()"
                class="space-y-4"
            >
                <div x-show="loading" class="text-gray-500 dark:text-gray-400 animate-pulse">
                    Loading editor...
                </div>

                <div x-show="!loading" class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden" style="height: 400px;">
                    <div class="monaco-editor-container h-full"></div>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        x-show="phpReady"
                        @click="run()"
                        x-bind:disabled="running"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-full font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <span x-show="!running">Run</span>
                        <span x-show="running">Running...</span>
                    </button>

                    <button
                        @click="resetCode()"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-600 border border-transparent rounded-full font-semibold text-sm text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Reset
                    </button>

                    <button
                        x-show="output !== ''"
                        @click="clearOutput()"
                        class="inline-flex items-center px-5 py-2.5 bg-gray-500 border border-transparent rounded-full font-semibold text-sm text-white shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Clear Output
                    </button>

                    <span x-show="loadingPhp && !phpReady" class="text-sm text-gray-500 dark:text-gray-400 animate-pulse">
                        Loading PHP runtime...
                    </span>
                </div>

                <div x-show="output !== ''" class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm whitespace-pre-wrap max-h-96 overflow-y-auto">
                    <div class="text-gray-500 text-xs mb-1 uppercase tracking-wider">Output</div>
                    <div x-text="output"></div>
                </div>
            </div>
        </div>
    </div>
</div>
