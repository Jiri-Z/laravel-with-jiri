const MONACO_CDN = 'https://cdn.jsdelivr.net/npm/monaco-editor@0.52.2/min/vs';

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

export function codingViewer(config) {
    return {
        editor: null,
        php: null,
        output: '',
        loading: true,
        loadingPhp: false,
        phpReady: false,
        running: false,
        checking: false,
        result: null,
        completedAtStart: config.completed,
        lastOutput: '',

        async init() {
            this.$nextTick(() => this.boot());
        },

        async boot() {
            try {
                const monaco = await loadMonaco();
                const container = this.$el.querySelector('.monaco-editor-container');
                this.editor = monaco.editor.create(container, {
                    value: config.initialCode,
                    language: 'php',
                    theme: 'vs-dark',
                    minimap: { enabled: false },
                    fontSize: 14,
                    automaticLayout: true,
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
                    this.lastOutput += `${config.translations?.error_prefix ?? '[PHP Error]: '}${event.detail}`;
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
            this.result = null;
            try {
                const code = this.editor.getValue();
                await this.php.run(code);
                this.output = this.lastOutput;
            } catch (e) {
                this.output = config.translations?.error ?? `Error: ${e.message}`;
            }
            this.running = false;
        },

        async check() {
            if (!this.phpReady || this.checking || this.running) return;
            if (this.completedAtStart) return;
            this.checking = true;
            this.output = '';
            this.lastOutput = '';
            this.result = null;
            try {
                const userCode = this.editor.getValue();
                const combinedCode = userCode + '\n' + config.testCode;
                await this.php.run(combinedCode);
                const actualOutput = this.lastOutput.trim();
                const expectedOutput = config.expectedOutput.trim();

                if (actualOutput === expectedOutput) {
                    this.result = 'correct';
                    this.$wire.markCodingComplete();
                } else {
                    this.result = 'incorrect';
                }
                this.output = actualOutput;
            } catch (e) {
                this.output = config.translations?.error ?? `Error: ${e.message}`;
                this.result = 'incorrect';
            }
            this.checking = false;
        },
    };
}
