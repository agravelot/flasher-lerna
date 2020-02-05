//Get the button:
const returnToTopButton: HTMLElement|null = document.getElementById("return-to-top");
const navbar: HTMLElement|null = document.getElementById('navMenu');

if (returnToTopButton && navbar) {

    returnToTopButton.addEventListener('click', () => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    });

    const handleIntersection: IntersectionObserverCallback = async (
        entries: IntersectionObserverEntry[],
    ): Promise<void> => {
        entries.forEach((entry: IntersectionObserverEntry) => {
            returnToTopButton.style.visibility = entry.isIntersecting ? "hidden" : "visible";
            returnToTopButton.style.opacity = entry.isIntersecting ? "0" : "1";
        });
    };

    const options: IntersectionObserverInit = {
        root: null,
        rootMargin: '20px',
        threshold: 1.0
    };

    const observer = new IntersectionObserver(handleIntersection, options);
    observer.observe(navbar);
}
