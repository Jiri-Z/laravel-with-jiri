import { repl } from './repl';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('repl', repl);
});
