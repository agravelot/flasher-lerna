import Sidebar from '../admin/views/Sidebar.vue';
import AlbumsMasonry from '../../../modules/Album/Resources/assets/js/components/front/AlbumsMasonry.vue';
import AlbumsShowGallery from '../../../modules/Album/Resources/assets/js/components/front/AlbumsShowGallery.vue';
import Vue from 'vue';
import router from './router';
// import '../app.ts'
import './menu.ts';
import './fontawsome.ts';
import '../bootstrap';
import '../fontawsome';
import '../bulma';
import '../vue-masonry';
import 'bulma-modal-fx/src/_js/modal-fx';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { Sidebar, AlbumsMasonry, AlbumsShowGallery },
    router,
});
