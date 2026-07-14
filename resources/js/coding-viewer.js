import { createEditor, createPhpRuntime } from './php-wasm';

export function codingViewer(config) {
    return {
        editor: null,
        php: null,
        output: '',
        status: 'loading',
        phpReady: false,
        running: false,
        checking: false,
        result: null,
        completedAtStart: config.completed,

        async init() {
            this.$nextTick(() => this.boot());
        },

        async boot() {
            const container = this.$el.querySelector('.editor-container');

            const result = await createEditor(container, config.initialCode, 'php');
            this.editor = result.editor;

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
                const onError = (event) => { outputAccumulator += (config.translations?.error_prefix || '[PHP Error]: ') + event.detail; };
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
            this.result = null;
            try {
                const code = this.editor.getValue();
                await this.php.run(code);
                this.output = this._getOutput();
            } catch (e) {
                this.output = config.translations?.error || 'Error: ' + e.message;
            }
            this.running = false;
        },

        async check() {
            if (!this.phpReady || this.checking || this.running) return;
            if (this.completedAtStart) return;
            this.checking = true;
            this.output = '';
            this._resetOutput();
            this.result = null;

            try {
                const userCode = this.editor.getValue();
                const userHasPhpTag = /^<\?php/i.test(userCode.trim());
                const testHasPhpTag = /^<\?php/i.test(config.testCode.trim());

                let combinedCode;
                if (userHasPhpTag && testHasPhpTag) {
                    combinedCode = userCode + '\n' + config.testCode.replace(/^<\?php\s*/i, '');
                } else if (!userHasPhpTag && testHasPhpTag) {
                    combinedCode = '<?php ' + userCode + '\n' + config.testCode.replace(/^<\?php\s*/i, '');
                } else {
                    combinedCode = userCode + '\n' + config.testCode;
                }

                await this.php.run(combinedCode);
                const actualOutput = this._getOutput().trim();
                const expectedOutput = config.expectedOutput.trim();

                if (actualOutput === expectedOutput) {
                    this.result = 'correct';
                    this.$wire.markCodingComplete(actualOutput);
                } else {
                    this.result = 'incorrect';
                }
                this.output = actualOutput;
            } catch (e) {
                this.output = config.translations?.error || 'Error: ' + e.message;
                this.result = 'incorrect';
            }
            this.checking = false;
        },

        handleKeydown(event) {
            if (event.ctrlKey && event.shiftKey && event.key === 'Enter') {
                event.preventDefault();
                this.check();
            } else if (event.ctrlKey && event.key === 'Enter') {
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
