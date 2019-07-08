/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import AlbumsMasonry from '../../modules/Album/Resources/assets/js/components/front/AlbumsMasonry.vue';
import './fontawsome';
import './bulma.ts';
import './bootstrap.ts';
import 'bulma-modal-fx/dist/js/modal-fx.js';

const app = new Vue({
    el: '#app',
    components: { AlbumsMasonry },
});

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
