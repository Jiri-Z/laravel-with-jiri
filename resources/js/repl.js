export function repl(config) {
    return {
        output: '',
        status: 'loading',
        phpReady: false,
        running: false,

        init() {
            this.$nextTick(() => this.boot());
        },

        boot() {
            this.initPhp();
        },

        async initPhp() {
            try {
                const { PhpWeb } = await import('https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs');
                this.php = new PhpWeb();

                let outputAccumulator = '';
                this.php.addEventListener('output', (event) => {
                    outputAccumulator += event.detail;
                });
                this.php.addEventListener('error', (event) => {
                    outputAccumulator += `[PHP Error]: ${event.detail}`;
                });

                this._getOutput = () => outputAccumulator;
                this._resetOutput = () => { outputAccumulator = ''; };

                await new Promise((resolve) => {
                    this.php.addEventListener('ready', () => resolve());
                });

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
                const code = this.$refs.codeEditor.value;
                await this.php.run(code);
                this.output = this._getOutput();
            } catch (e) {
                this.output = 'Error: ' + e.message;
            }
            this.running = false;
        },

        resetCode() {
            const defaultCode = '<?php\n\necho "Hello, World!";\n';
            this.$refs.codeEditor.value = defaultCode;
            this.output = '';
            this._resetOutput();
        },

        clearOutput() {
            this.output = '';
            this._resetOutput();
        },

        handleKeydown(event) {
            if (event.ctrlKey && event.key === 'Enter') {
                event.preventDefault();
                this.run();
            }
        },

        destroy() {
            if (this.php) {
                if (this.php.terminate) {
                    this.php.terminate();
                }
            }
        },
    };
}
