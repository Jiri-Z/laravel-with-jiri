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

            const result = await createEditor(container, savedCode, 'php');
            this.editor = result.editor;

            this.editor.onDidChangeModelContent(() => {
                try { localStorage.setItem(LS_KEY, this.editor.getValue()); } catch { /* localStorage unavailable */ }
            });

            this.status = 'loading-php';
            this.initPhp();
        },

        async initPhp() {
            try {
                const { php, ready } = await createPhpRuntime();
                this.php = php;

                let outputAccumulator = '';
                this._phpListeners = [];
                const onOutput = (event) => { outputAccumulator += event.detail; };
                const onError = (event) => { outputAccumulator += `[PHP Error]: ${event.detail}`; };
                php.addEventListener('output', onOutput);
                php.addEventListener('error', onError);
                this._phpListeners.push({ event: 'output', fn: onOutput });
                this._phpListeners.push({ event: 'error', fn: onError });

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
            if (this.php) {
                if (this._phpListeners) {
                    for (const { event, fn } of this._phpListeners) {
                        this.php.removeEventListener(event, fn);
                    }
                }
                if (this.php.terminate) {
                    this.php.terminate();
                }
            }
            if (this.editor && this.editor.dispose) {
                this.editor.dispose();
            }
        },
    };
}
