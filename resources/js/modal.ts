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
            }
        }
    })();

    modalFX.init();

})();
