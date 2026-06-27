@php $quizContent = $step->getContentAsArray(); @endphp
<div>
    @if ($completed)
        <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">Step completed successfully</span>
        </div>
    @endif

    <div class="mb-6">
        <div class="prose dark:prose-invert max-w-none mb-4">
            {!! nl2br(e($quizContent['prompt'] ?? '')) !!}
        </div>

        <div
            x-data="codingViewer({
                stepId: @js($step->id),
                initialCode: @js($quizContent['initial_code'] ?? ''),
                testCode: @js($quizContent['test_code'] ?? ''),
                expectedOutput: @js($quizContent['expected_output'] ?? ''),
                completed: @json($completed),
            })"
            x-init="init()"
            class="space-y-4"
        >
            <div x-show="loading" class="text-gray-500 dark:text-gray-400 animate-pulse">
                Loading code editor...
            </div>

            <div x-show="!loading" class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden" style="height: 400px;">
                <div class="monaco-editor-container h-full"></div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    x-show="!completed && phpReady"
                    @click="run()"
                    x-bind:disabled="running"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <span x-show="!running">Run Code</span>
                    <span x-show="running">Running...</span>
                </button>

                <button
                    x-show="!completed && phpReady"
                    @click="check()"
                    x-bind:disabled="checking || running"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <span x-show="!checking">Check Answer</span>
                    <span x-show="checking">Checking...</span>
                </button>

                <span x-show="loadingPhp && !phpReady" class="text-sm text-gray-500 dark:text-gray-400 animate-pulse">
                    Loading PHP runtime (first time may take a moment)...
                </span>
            </div>

            <div x-show="output !== ''" class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm whitespace-pre-wrap max-h-48 overflow-y-auto">
                <div class="text-gray-500 text-xs mb-1 uppercase tracking-wider">Output</div>
                <div x-text="output"></div>
            </div>

            <div x-show="result === 'correct'" class="flex items-center gap-2 text-green-600 dark:text-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">Correct! Step completed.</span>
            </div>

            <div x-show="result === 'incorrect'" class="flex items-center gap-2 text-red-600 dark:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">Incorrect, try again.</span>
            </div>
        </div>
    </div>
</div>
