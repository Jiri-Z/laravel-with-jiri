import { codingViewer } from './coding-viewer';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('codingViewer', codingViewer);
});
