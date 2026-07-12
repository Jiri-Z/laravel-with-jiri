import { codingViewer } from './coding-viewer';
import { repl } from './repl';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('codingViewer', codingViewer);
    window.Alpine.data('repl', repl);
});
