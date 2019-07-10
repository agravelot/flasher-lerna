// Trigger modals
(function() {
    let modalFX = (function() {
        let elements = {
            target: 'data-target',
            active: 'is-active',
            button: 'modal-button',
            close: 'modal-close',
            buttonClose: 'modal-button-close',
            background: 'modal-background',
            navigable: 'is-modal-navigable',
        };

        let onClickEach = function(selector, callback) {
            let arr = document.getElementsByClassName(selector);
            Array.from(arr).forEach(function(el) {
                el.addEventListener('click', callback);
            });
        };

        let events = function() {
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

        let closeAll = function() {
            let openModal = document.getElementsByClassName(elements.active);
            Array.from(openModal).forEach(function(modal) {
                modal.classList.remove(elements.active);
            });
            unFreeze();
        };

        let openModal = function() {
            let targetModal = this.getAttribute(elements.target);
            freeze();
            let modal: Element = document.getElementById(targetModal);
            modal.classList.add(elements.active);

            let image = modal.getElementsByTagName('img')[0];
            image.classList.add('responsive-media');

            image.sizes = `${Math.ceil(
                (image.getBoundingClientRect().width / window.innerWidth) * 100
            )}vw`;
        };

        let closeModal = function() {
            let targetModal = this.parentElement.id;
            let modal: Element = document.getElementById(targetModal);
            modal.classList.remove(elements.active);
            unFreeze();

            // Remove 'responsive-media' class
            let image = modal.getElementsByTagName('img')[0];
            image.classList.remove('responsive-media');
        };

        // Freeze scrollbars
        let freeze = function() {
            document.getElementsByTagName('html')[0].style.overflow = 'hidden';
            document.getElementsByTagName('body')[0].style.overflowY = 'scroll';
        };

        let unFreeze = function() {
            document.getElementsByTagName('html')[0].style.overflow = '';
            document.getElementsByTagName('body')[0].style.overflowY = '';
        };

        return {
            init: function() {
                events();

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

                function previousModal() {
                    let currentActiveModal: Element = getCurrentModal();
                    let previousModal: Element = currentActiveModal.previousElementSibling;

                    if (!previousModal || !previousModal.classList.contains(elements.navigable)) {
                        return;
                    }

                    closeModal(currentActiveModal);
                    openModal(previousModal);
                }

                function nextModal() {
                    let currentActiveModal: Element = getCurrentModal();
                    let nextModal: Element = currentActiveModal.nextElementSibling;

                    if (!nextModal || !nextModal.classList.contains(elements.navigable)) {
                        return;
                    }

                    closeModal(currentActiveModal);
                    openModal(nextModal);
                }

                function getCurrentModal(): Element {
                    return document.getElementsByClassName(
                        elements.navigable + ' ' + elements.active
                    )[0];
                }

                function closeModal(modal: Element): void {
                    modal.classList.remove(elements.active);
                    let image = modal.getElementsByTagName('img')[0];
                    image.classList.remove('responsive-media');
                }

                function openModal(modal: Element): void {
                    modal.classList.add(elements.active);
                    let image = modal.getElementsByTagName('img')[0];
                    image.classList.add('responsive-media');

                    image.sizes = `${Math.ceil(
                        (image.getBoundingClientRect().width / window.innerWidth) * 100
                    )}vw`;
                }
            },
        };
    })();

    modalFX.init();
})();
