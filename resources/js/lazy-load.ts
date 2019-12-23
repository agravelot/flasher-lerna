document.addEventListener('DOMContentLoaded', () => {
    const ratio = 0.01;

    const options: object = {
        root: null,
        rootMargin: '0px',
        threshold: ratio,
    };

    const handleIntersection: IntersectionObserverCallback = (
        entries: IntersectionObserverEntry[],
        observer: IntersectionObserver
    ) => {
        entries.forEach((entry: IntersectionObserverEntry) => {
            if (entry.intersectionRatio > ratio) {
                const computedSize: number = Math.ceil(
                    (entry.target.getBoundingClientRect().width / window.innerWidth) * 100
                );
                // Avoid to set 0vw and load full sized image
                if (computedSize !== 0) {
                    (entry.target as HTMLImageElement).sizes = `${computedSize}vw`;
                    entry.target.classList.add('responsive-media');
                }
                observer.unobserve(entry.target);
            }
        });
    };

    const observer: IntersectionObserver = new IntersectionObserver(handleIntersection, options);
    Array.from(document.getElementsByClassName('responsive-media-lazy')).forEach((el: Element) => {
        observer.observe(el);
    });
});
