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

                        <div x-data="{ stepType: $wire.type }" x-init="$watch('$wire.type', v => stepType = v)">
                            {{-- Reading content --}}
                            <div x-show="stepType === 'reading'" x-cloak>
                                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('admin.label_content') }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                        {{ __('admin.label_content_hint') }}
                                    </span>
                                </label>
                                <textarea wire:model="content" id="content" rows="10" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Quiz builder --}}
                            <div x-show="stepType === 'quiz'" x-cloak class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('admin.quiz_questions') }}</h3>
                                    <button type="button" wire:click="addQuestion" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        + {{ __('admin.add_question') }}
                                    </button>
                                </div>

                                @error('questions') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                                @foreach ($questions as $qIndex => $question)
                                    <div wire:key="question-{{ $qIndex }}" class="border border-gray-300 dark:border-gray-700 rounded-lg p-4" x-data="{ expanded: true }">
                                        <div class="flex items-center justify-between mb-3">
                                            <button type="button" x-on:click="expanded = !expanded" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <span x-show="expanded">▼</span>
                                                <span x-show="!expanded">▶</span>
                                                {{ __('admin.question_number', ['number' => $qIndex + 1]) }}
                                            </button>
                                            <button type="button" wire:click="removeQuestion({{ $qIndex }})" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                {{ __('admin.remove_question') }}
                                            </button>
                                        </div>

                                        <div x-show="expanded" class="space-y-3">
                                            <div class="grid grid-cols-3 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('admin.question_type') }}</label>
                                                    <select wire:model.live="questions.{{ $qIndex }}.type" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="single">{{ __('admin.type_single') }}</option>
                                                        <option value="multiple">{{ __('admin.type_multiple') }}</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('admin.difficulty') }}</label>
                                                    <select wire:model.live="questions.{{ $qIndex }}.difficulty" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="easy">{{ __('admin.difficulty_easy') }}</option>
                                                        <option value="medium">{{ __('admin.difficulty_medium') }}</option>
                                                        <option value="hard">{{ __('admin.difficulty_hard') }}</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('admin.topic') }}</label>
                                                    <input wire:model.live="questions.{{ $qIndex }}.topic" type="text" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('admin.question_text') }}</label>
                                                <textarea wire:model.live="questions.{{ $qIndex }}.question" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                                @error("questions.{{ $qIndex }}.question") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <div class="flex items-center justify-between mb-1">
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('admin.options') }}</label>
                                                    <button type="button" wire:click="addOption({{ $qIndex }})" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                                        + {{ __('admin.add_option') }}
                                                    </button>
                                                </div>
                                                <div class="space-y-2">
                                                    @foreach ($question['options'] as $oIndex => $option)
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs text-gray-500 dark:text-gray-400 w-5">{{ chr(65 + $oIndex) }}.</span>
                                                            <input wire:model.live="questions.{{ $qIndex }}.options.{{ $oIndex }}" type="text" class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                            @if (count($question['options']) > 2)
                                                                <button type="button" wire:click="removeOption({{ $qIndex }}, {{ $oIndex }})" class="text-xs text-red-600 hover:text-red-800 dark:text-red-400">&times;</button>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $question['type'] === 'multiple' ? __('admin.correct_answers') : __('admin.correct_answer') }}
                                                </label>
                                                @if ($question['type'] === 'multiple')
                                                    <div class="mt-1 space-y-1">
                                                        @foreach ($question['options'] as $oIndex => $option)
                                                            @php
                                                                $answerArr = is_array($question['answer'] ?? null) ? $question['answer'] : [];
                                                            @endphp
                                                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                                <input type="checkbox" wire:model.live="questions.{{ $qIndex }}.answer.{{ $oIndex }}" value="{{ $oIndex }}"
                                                                    {{ in_array($oIndex, $answerArr) ? 'checked' : '' }}
                                                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                                    x-on:change="
                                                                        let answers = $wire.questions[{{ $qIndex }}].answer || [];
                                                                        if (!Array.isArray(answers)) answers = [];
                                                                        if ($event.target.checked) {
                                                                            if (!answers.includes({{ $oIndex }})) answers.push({{ $oIndex }});
                                                                        } else {
                                                                            answers = answers.filter(function(a) { return a !== {{ $oIndex }}; });
                                                                        }
                                                                        $wire.set('questions.{{ $qIndex }}.answer', answers);
                                                                    ">
                                                                {{ chr(65 + $oIndex) }}. {{ $option }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <select wire:model.live="questions.{{ $qIndex }}.answer" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="">{{ __('admin.select_answer') }}</option>
                                                        @foreach ($question['options'] as $oIndex => $option)
                                                            <option value="{{ $oIndex }}">{{ chr(65 + $oIndex) }}. {{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                                @error("questions.{{ $qIndex }}.answer") <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">{{ __('admin.explanation') }}</label>
                                                <textarea wire:model.live="questions.{{ $qIndex }}.explanation" rows="1" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if (count($questions) === 0)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">{{ __('admin.no_questions_yet') }}</p>
                                @endif
                            </div>

                            {{-- Coding fields --}}
                            <div x-show="stepType === 'coding'" x-cloak class="space-y-4">
                                <div>
                                    <label for="prompt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('admin.label_prompt') }}</label>
                                    <textarea wire:model="prompt" id="prompt" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    @error('prompt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div x-data="{ monacoLoaded: false, initMonaco() { if (this.monacoLoaded) return; this.monacoLoaded = true; import('https://cdn.jsdelivr.net/npm/monaco-editor@0.55.1/min/vs/loader.js').then(() => { require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.55.1/min/vs' } }); require(['vs/editor/editor.main'], (monaco) => { const initialEditor = monaco.editor.create($refs.initialCode, { value: $wire.initialCode, language: 'php', theme: 'vs-dark', minimap: { enabled: false }, fontSize: 14, automaticLayout: true }); initialEditor.onDidChangeModelContent(() => { $wire.set('initialCode', initialEditor.getValue()); }); const testEditor = monaco.editor.create($refs.testCode, { value: $wire.testCode, language: 'php', theme: 'vs-dark', minimap: { enabled: false }, fontSize: 14, automaticLayout: true }); testEditor.onDidChangeModelContent(() => { $wire.set('testCode', testEditor.getValue()); }); }); }); } }" x-init="stepType === 'coding' && initMonaco()" x-effect="stepType === 'coding' && initMonaco()">
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
                                {{ $step ? __('admin.submit_update', ['resource' => __('admin.resource_step')]) : __('admin.submit_create', ['resource' => __('admin.resource_step')]) }}
                            </button>
                            <a href="{{ route('admin.steps.index', [$course, $lesson]) }}" wire:navigate class="text-sm text-gray-600 dark:text-gray-400 hover:underline">{{ __('admin.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
