/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import AlbumsMasonry from '../../modules/Album/Resources/assets/js/components/front/AlbumsMasonry.vue';
import AlbumsShowGallery from '../../modules/Album/Resources/assets/js/components/front/AlbumsShowGallery.vue';
import './fontawsome';
import './bulma';
// No need to load axios since qe are not making any requests
// import './bootstrap';

const app = new Vue({
    el: '#app',
    components: { AlbumsMasonry, AlbumsShowGallery },
});
