import { createEditor, createPhpRuntime } from './php-wasm';

const LS_KEY = 'repl-code';

export function repl(config) {
    return {
        editor: null,
        php: null,
        output: '',
        status: 'loading',
        phpReady: false,
        running: false,
        lastOutput: '',

        async init() {
            this.$nextTick(() => this.boot());
        },

        async boot() {
            const defaultCode = '<?php\n\n// Write PHP code here\n';
            let savedCode;
            try { savedCode = localStorage.getItem(LS_KEY) || defaultCode; } catch { savedCode = defaultCode; }
            const container = this.$el.querySelector('.editor-container');

            try {
                const result = await createEditor(container, savedCode, 'php');
                this.editor = result.editor;

                this.editor.onDidChangeModelContent(() => {
                    try { localStorage.setItem(LS_KEY, this.editor.getValue()); } catch { /* localStorage unavailable */ }
                });
            } catch (e) {
                console.error('Failed to create editor:', e);
                container.innerHTML = '<textarea class="w-full h-full p-4 font-mono text-sm bg-gray-900 text-green-400 border-0 resize-none focus:outline-none" spellcheck="false">' + savedCode.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</textarea>';
                const ta = container.querySelector('textarea');
                this.editor = {
                    getValue: () => ta.value,
                    setValue: (v) => { ta.value = v; },
                    onDidChangeModelContent: (cb) => { ta.addEventListener('input', cb); },
                    dispose: () => { ta.remove(); },
                };
            }

            this.status = 'loading-php';
            this.initPhp();
        },

        async initPhp() {
            try {
                const { php, ready } = await createPhpRuntime();
                this.php = php;

                let outputAccumulator = '';
                php.addEventListener('output', (event) => {
                    outputAccumulator += event.detail;
                });
                php.addEventListener('error', (event) => {
                    outputAccumulator += `[PHP Error]: ${event.detail}`;
                });

                this._getOutput = () => outputAccumulator;
                this._resetOutput = () => { outputAccumulator = ''; };

                await ready;
                this.phpReady = true;
                this.status = 'ready';
            } catch (e) {
                console.error('Failed to load PHP WASM:', e);
                this.status = 'error';
                this.output = 'Failed to load PHP runtime: ' + e.message;
            }
        },

        async run() {
            if (!this.phpReady || this.running) return;
            this.running = true;
            this.output = '';
            this._resetOutput();
            try {
                const code = this.editor.getValue();
                await this.php.run(code);
                this.output = this._getOutput();
            } catch (e) {
                this.output = 'Error: ' + e.message;
            }
            this.running = false;
        },

        clearOutput() {
            this.output = '';
            this._resetOutput();
        },

        resetCode() {
            const defaultCode = '<?php\n\n// Write PHP code here\n';
            this.editor.setValue(defaultCode);
            try { localStorage.setItem(LS_KEY, defaultCode); } catch { /* localStorage unavailable */ }
            this.clearOutput();
        },

        handleKeydown(event) {
            if (event.ctrlKey && event.key === 'Enter') {
                event.preventDefault();
                this.run();
            }
        },

        destroy() {
            if (this.editor && this.editor.dispose) {
                this.editor.dispose();
            }
            if (this.php && this.php.terminate) {
                this.php.terminate();
            }
        },
    };
}
