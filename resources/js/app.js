/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./fontawsome');

// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', function() {
    //Functions
    function getAll(selector) {
        return Array.prototype.slice.call(document.querySelectorAll(selector), 0);
    }

    // Get all "navbar-burger" elements
    const $navbarBurgers = getAll('.navbar-burger');

    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {
        // Add a click event on each of them
        $navbarBurgers.forEach(function($el) {
            $el.addEventListener('click', function() {
                // Get the target from the "data-target" attribute
                let target = $el.dataset.target;
                let $target = document.getElementById(target);

                // Toggle the class on both the "navbar-burger" and the "navbar-menu"
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    }

    // Modals
    let rootEl = document.documentElement;
    let $modals = getAll('.modal');
    let $modalButtons = getAll('.modal-button');
    let $modalCloses = getAll(
        '.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button'
    );

    if ($modalButtons.length > 0) {
        $modalButtons.forEach(function($el) {
            $el.addEventListener('click', function() {
                let target = $el.dataset.target;
                openModal(target);
            });
        });
    }

    if ($modalCloses.length > 0) {
        $modalCloses.forEach(function($el) {
            $el.addEventListener('click', function() {
                closeModals();
            });
        });
    }

    function openModal(target) {
        let $target = document.getElementById(target);
        rootEl.classList.add('is-clipped');
        $target.classList.add('is-active');
    }

    function closeModals() {
        rootEl.classList.remove('is-clipped');
        $modals.forEach(function($el) {
            $el.classList.remove('is-active');
        });
    }

    document.addEventListener('keydown', function(event) {
        let e = event || window.event;
        if (e.keyCode === 27) {
            closeModals();
            //closeDropdowns();
        }
    });
});
