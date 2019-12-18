// Trigger modals
(function(): void {
    const modalFX = (function(): { init: () => void } {
        const elements = {
            target: 'data-target',
            active: 'is-active',
            button: 'modal-button',
            close: 'modal-close',
            buttonClose: 'modal-button-close',
            background: 'modal-background',
            navigable: 'is-modal-navigable',
        };

        const onClickEach = function(selector: string, callback: () => void): void {
            const elements = document.getElementsByClassName(selector);
            Array.from(elements).forEach((el) => el.addEventListener('click', callback));
        };

        // Freeze scrollbars
        const freeze = function(): void {
            document.getElementsByTagName('html')[0].style.overflow = 'hidden';
            document.getElementsByTagName('body')[0].style.overflowY = 'scroll';
        };

        const unFreeze = function(): void {
            document.getElementsByTagName('html')[0].style.overflow = '';
            document.getElementsByTagName('body')[0].style.overflowY = '';
        };

        const closeAll = function(): void {
            const openModal = document.getElementsByClassName(elements.active);
            Array.from(openModal).forEach((modal) => {
                modal.classList.remove(elements.active);
            });
            unFreeze();
        };

        const openModal = function(): void {
            const targetModal = this.getAttribute(elements.target);
            freeze();
            const modal: HTMLElement | null = document.getElementById(targetModal);
            modal?.classList.add(elements.active);

            const images = modal?.getElementsByTagName('img');
            Array.from(images || []).forEach((el: HTMLImageElement) => {
                el.sizes = `${Math.ceil(
                    (el.getBoundingClientRect().width / window.innerWidth) * 100
                )}vw`;
                el?.classList.add('responsive-media');
            });
        };

        const closeModal = function(): void {
            const targetModal = this.parentElement.id;
            const modal: HTMLElement | null = document.getElementById(targetModal);
            modal?.classList.remove(elements.active);
            unFreeze();

            // Remove 'responsive-media' class
            const images = modal?.getElementsByTagName('img');
            Array.from(images || []).forEach((el: HTMLImageElement) => el.classList.remove('responsive-media'));
        };

        const events = function(): void {
            onClickEach(elements.button, openModal);

            onClickEach(elements.close, closeModal);
            onClickEach(elements.buttonClose, closeAll);
            onClickEach(elements.background, closeModal);

            // Close all modals if ESC key is pressed
            document.addEventListener('keyup', function(event) {
                if (event.key === 'Escape') {
                    closeAll();
                }
            });
        };

        return {
            init: function(): void {
                events();

                function getCurrentModal(): Element {
                    return document.getElementsByClassName(
                        elements.navigable + ' ' + elements.active
                    )[0];
                }

                function closeModal(modal: Element): void {
                    modal.classList.remove(elements.active);
                    const images = modal.getElementsByTagName('img');
                    Array.from(images || []).forEach((el: HTMLImageElement) => {
                        el.classList.remove('responsive-media');
                    });
                }

                function openModal(modal: Element): void {
                    modal.classList.add(elements.active);
                    const images = modal.getElementsByTagName('img');
                    Array.from(images || []).forEach((el: HTMLImageElement) => {
                        el.sizes = `${Math.ceil(
                            (el.getBoundingClientRect().width / window.innerWidth) * 100
                        )}vw`;
                        el.classList.add('responsive-media');
                    });
                }

                function previousModal(): void {
                    const currentActiveModal: Element = getCurrentModal();
                    const previousModal: Element | null = currentActiveModal.previousElementSibling;

                    if (!previousModal?.classList.contains(elements.navigable)) {
                        return;
                    }

                    closeModal(currentActiveModal);
                    openModal(previousModal);
                }

                function nextModal(): void {
                    const currentActiveModal: Element = getCurrentModal();
                    const nextModal: Element | null = currentActiveModal.nextElementSibling;

                    if (!nextModal?.classList.contains(elements.navigable)) {
                        return;
                    }

                    closeModal(currentActiveModal);
                    openModal(nextModal);
                }

                window.addEventListener(
                    'keydown',
                    function(event) {
                        if (event.defaultPrevented) {
                            return; // Do nothing if the event was already processed
                        }

                        switch (event.key) {
                            case 'Left': // IE/Edge specific value
                                previousModal();
                                break;
                            case 'ArrowLeft':
                                previousModal();
                                break;
                            case 'Right': // IE/Edge specific value
                                nextModal();
                                break;
                            case 'ArrowRight':
                                nextModal();
                                break;
                            default:
                                return; // Quit when this doesn't handle the key event.
                        }

                        // Cancel the default action to avoid it being handled twice
                        event.preventDefault();
                    },
                    true
                );
            },
        };
    })();

    modalFX.init();
})();
