// Trigger modals
(function () {
    let modalFX = (function () {

        let elements = {
            target: 'data-target',
            active: 'is-active',
            button: '.modal-button',
            close: '.modal-close',
            buttonClose: '.modal-button-close',
            background: '.modal-background'
        };

        let onClickEach = function (selector, callback) {
            let arr = document.querySelectorAll(selector);
            arr.forEach(function (el) {
                el.addEventListener('click', callback);
            })
        };

        let events = function () {
            onClickEach(elements.button, openModal);

            onClickEach(elements.close, closeModal);
            onClickEach(elements.buttonClose, closeAll);
            onClickEach(elements.background, closeModal);

            // Close all modals if ESC key is pressed
            document.addEventListener('keyup', function(key){
                if(key.keyCode == 27) {
                    closeAll();
                }
            });
        };

        let closeAll = function() {
            let openModal = document.querySelectorAll('.' + elements.active);
            openModal.forEach(function (modal) {
                modal.classList.remove(elements.active);
            });
            unFreeze();
        };

        let openModal = function () {
            let modal = this.getAttribute(elements.target);
            freeze();
            document.getElementById(modal).classList.add(elements.active);
        };

        let closeModal = function () {
            let modal = this.parentElement.id;
            document.getElementById(modal).classList.remove(elements.active);
            unFreeze();
        };

        // Freeze scrollbars
        let freeze = function () {
            document.getElementsByTagName('html')[0].style.overflow = "hidden";
            document.getElementsByTagName('body')[0].style.overflowY = "scroll";
        };

        let unFreeze = function () {
            document.getElementsByTagName('html')[0].style.overflow = "";
            document.getElementsByTagName('body')[0].style.overflowY = "";
        };

        return {
            init: function () {
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

                    if (!previousModal || !previousModal.classList.contains('is-modal-navigable')) {
                        return;
                    }

                    closeModal(currentActiveModal);
                    openModal(previousModal);
                }

                function nextModal() {
                    let currentActiveModal: Element = getCurrentModal();
                    let nextModal: Element = currentActiveModal.nextElementSibling;

                    if (!nextModal || !nextModal.classList.contains('is-modal-navigable')) {
                        return;
                    }

                    closeModal(currentActiveModal);
                    openModal(nextModal);
                }

                function getCurrentModal(): Element {
                    return document.getElementsByClassName('is-modal-navigable is-active')[0];
                }

                function closeModal(modal: Element): void {
                    modal.classList.remove('is-active');
                }

                function openModal(modal: Element): void {
                    modal.classList.add('is-active');
                }


            }
        }
    })();

    modalFX.init();

})();
