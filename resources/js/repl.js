const MONACO_CDN = 'https://cdn.jsdelivr.net/npm/monaco-editor@0.55.1/min/vs';
const LS_KEY = 'repl-code';

function loadMonaco() {
    if (window.monaco) return Promise.resolve(window.monaco);
    return new Promise((resolve) => {
        const script = document.createElement('script');
        script.src = `${MONACO_CDN}/loader.js`;
        script.onload = () => {
            require.config({ paths: { vs: MONACO_CDN } });
            require(['vs/editor/editor.main'], (monaco) => {
                resolve(monaco);
            });
        };
        document.head.appendChild(script);
    });
}

function loadPhpWasm() {
    return import('https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs');
}

export function repl(config) {
    return {
        editor: null,
        php: null,
        output: '',
        loading: true,
        loadingPhp: false,
        phpReady: false,
        running: false,
        lastOutput: '',

        async init() {
            this.$nextTick(() => this.boot());
        },

        async boot() {
            const savedCode = localStorage.getItem(LS_KEY) || '<?php\n\n// Write PHP code here\n';

            try {
                const monaco = await loadMonaco();
                const container = this.$el.querySelector('.monaco-editor-container');
                this.editor = monaco.editor.create(container, {
                    value: savedCode,
                    language: 'php',
                    theme: 'vs-dark',
                    minimap: { enabled: false },
                    fontSize: 14,
                    automaticLayout: true,
                });

                this.editor.onDidChangeModelContent(() => {
                    localStorage.setItem(LS_KEY, this.editor.getValue());
                });

                this.loading = false;
            } catch (e) {
                console.error('Failed to load Monaco editor:', e);
                this.loading = false;
            }

            this.initPhp();
        },

        async initPhp() {
            this.loadingPhp = true;
            try {
                const { PhpWeb } = await loadPhpWasm();
                this.php = new PhpWeb();

                this.php.addEventListener('ready', () => {
                    this.phpReady = true;
                });

                this.php.addEventListener('output', (event) => {
                    this.lastOutput += event.detail;
                });

                this.php.addEventListener('error', (event) => {
                    this.lastOutput += `[PHP Error]: ${event.detail}`;
                });
            } catch (e) {
                console.error('Failed to load PHP WASM:', e);
            }
            this.loadingPhp = false;
        },

        async run() {
            if (!this.phpReady || this.running) return;
            this.running = true;
            this.output = '';
            this.lastOutput = '';
            try {
                const code = this.editor.getValue();
                await this.php.run(code);
                this.output = this.lastOutput;
            } catch (e) {
                this.output = `Error: ${e.message}`;
            }
            this.running = false;
        },

        clearOutput() {
            this.output = '';
            this.lastOutput = '';
        },

        resetCode() {
            const defaultCode = '<?php\n\n// Write PHP code here\n';
            this.editor.setValue(defaultCode);
            localStorage.setItem(LS_KEY, defaultCode);
            this.clearOutput();
        },
    };
}
