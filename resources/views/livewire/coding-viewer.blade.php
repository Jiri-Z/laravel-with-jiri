@php
    $codingData = $step->getCodingData();
    $translations = [
        'loading' => __('steps.coding_loading'),
        'run_code' => __('steps.coding_run'),
        'running' => __('steps.coding_running'),
        'check_answer' => __('steps.coding_check'),
        'checking' => __('steps.coding_checking'),
        'loading_runtime' => __('steps.coding_loading_runtime'),
        'output' => __('steps.coding_output'),
        'correct' => __('steps.coding_correct'),
        'incorrect' => __('steps.coding_incorrect'),
        'step_completed' => __('steps.coding_step_completed'),
        'error' => __('steps.coding_error'),
        'error_prefix' => __('steps.coding_error_prefix'),
    ];
@endphp
<div>
    @if ($completed)
        <div class="flex items-center gap-2 text-green-600 dark:text-green-400 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium">{{ __('steps.coding_step_completed') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <div class="prose dark:prose-invert max-w-none mb-4">
            {!! nl2br(e($codingData['prompt'])) !!}
        </div>

        <div
            x-data="codingViewer({
                stepId: @js($step->id),
                initialCode: @js($codingData['initial_code']),
                testCode: @js($codingData['test_code']),
                expectedOutput: @js($codingData['expected_output']),
                completed: @json($completed),
                translations: @json($translations)
            })"
            x-init="init()"
            class="space-y-4"
        >
            <div x-show="loading" class="text-gray-500 dark:text-gray-400 animate-pulse">
                <span x-text="translations.loading"></span>
            </div>

            <div x-show="!loading" class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden" style="height: 400px;">
                <div class="monaco-editor-container h-full"></div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    x-show="!completed && phpReady"
                    @click="run()"
                    x-bind:disabled="running"
                    class="inline-flex items-center px-5 py-2.5 bg-gray-600 border border-transparent rounded-full font-semibold text-sm text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <span x-show="!running" x-text="translations.run_code"></span>
                    <span x-show="running" x-text="translations.running"></span>
                </button>

                <button
                    x-show="!completed && phpReady"
                    @click="check()"
                    x-bind:disabled="checking || running"
                    class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-full font-semibold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <span x-show="!checking" x-text="translations.check_answer"></span>
                    <span x-show="checking" x-text="translations.checking"></span>
                </button>

                <span x-show="loadingPhp && !phpReady" class="text-sm text-gray-500 dark:text-gray-400 animate-pulse" x-text="translations.loading_runtime"></span>
            </div>

            <div x-show="output !== ''" class="bg-gray-900 text-green-400 p-4 rounded-lg font-mono text-sm whitespace-pre-wrap max-h-48 overflow-y-auto">
                <div class="text-gray-500 text-xs mb-1 uppercase tracking-wider" x-text="translations.output"></div>
                <div x-text="output"></div>
            </div>

            <div x-show="result === 'correct'" class="flex items-center gap-2 text-green-600 dark:text-green-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium" x-text="translations.correct"></span>
            </div>

            <div x-show="result === 'incorrect'" class="flex items-center gap-2 text-red-600 dark:text-red-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium" x-text="translations.incorrect"></span>
            </div>
        </div>
    </div>
</div>
