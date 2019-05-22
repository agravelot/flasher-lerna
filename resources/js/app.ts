/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import AlbumsMasonry from '../../modules/Album/Resources/assets/js/components/front/AlbumsMasonry.vue';
import AlbumsShowGallery from '../../modules/Album/Resources/assets/js/components/front/AlbumsShowGallery.vue';
import './bootstrap';
import './fontawsome';
import './bulma';
import './vue-masonry';
import 'bulma-modal-fx/src/_js/modal-fx';

const app = new Vue({
    el: '#app',
    components: { AlbumsMasonry, AlbumsShowGallery },
});
