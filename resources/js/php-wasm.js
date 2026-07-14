const MONACO_CDN = 'https://cdn.jsdelivr.net/npm/monaco-editor@0.55.1/min/vs';
let phpWasmModule = null;

export function loadMonaco() {
    if (window.monaco) return Promise.resolve(window.monaco);
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = `${MONACO_CDN}/loader.js`;
        script.onload = () => {
            require.config({ paths: { vs: MONACO_CDN } });
            require(['vs/editor/editor.main'], (monaco) => {
                resolve(monaco);
            }, reject);
        };
        script.onerror = () => reject(new Error('Failed to load Monaco editor from CDN'));
        document.head.appendChild(script);
    });
}

export function loadPhpWasm() {
    if (phpWasmModule) return phpWasmModule;
    phpWasmModule = import('https://cdn.jsdelivr.net/npm/php-wasm/PhpWeb.mjs').catch((err) => {
        phpWasmModule = null;
        throw err;
    });
    return phpWasmModule;
}

export function createEditor(container, value, language) {
    return loadMonaco()
        .then((monaco) => {
            const editor = monaco.editor.create(container, {
                value,
                language,
                theme: 'vs-dark',
                minimap: { enabled: false },
                fontSize: 14,
                automaticLayout: true,
            });
            return { editor, isMonaco: true };
        })
        .catch(() => {
            const textarea = document.createElement('textarea');
            textarea.value = value;
            textarea.className = 'w-full h-full p-4 font-mono text-sm bg-gray-900 text-green-400 border-0 resize-none focus:outline-none';
            textarea.spellcheck = false;
            container.innerHTML = '';
            container.className = 'h-full';
            container.appendChild(textarea);
            return {
                editor: {
                    getValue: () => textarea.value,
                    setValue: (v) => { textarea.value = v; },
                    onDidChangeModelContent: (cb) => {
                        textarea.addEventListener('input', cb);
                    },
                    dispose: () => { textarea.remove(); },
                },
                isMonaco: false,
            };
        });
}

export function createPhpRuntime() {
    return loadPhpWasm()
        .then(({ PhpWeb }) => {
            const php = new PhpWeb();
            let readyResolve;
            const ready = new Promise((resolve) => { readyResolve = resolve; });

            php.addEventListener('ready', () => readyResolve());
            php.addEventListener('output', () => {});
            php.addEventListener('error', () => {});

            return { php, ready };
        });
}
