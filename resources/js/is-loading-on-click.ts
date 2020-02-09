const elements = document.getElementsByClassName('is-loading-on-click');

Array.from(elements || []).forEach(e => {
    e.addEventListener('click', () => {
        e.classList.add('is-loading');
        e.addEventListener('blur', () => {
            e.classList.contains('is-loading') && e.classList.remove('is-loading');
        });
    });
});
