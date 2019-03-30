/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from "vue"
import App from './admin/views/App.vue';
import router from './router';

import 'bulma-modal-fx/src/_js/modal-fx';
import './bootstrap'
import './admin/menu';
import './fontawsome';
import './admin/fontawsome';
import './bulma'
import './vue-masonry'
// import './dropzone';

import AlbumsMasonry from '../../modules/Album/Resources/assets/js/components/front/AlbumsMasonry.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: {App},
    router,
});

Vue.component('albums-masonry', AlbumsMasonry);

