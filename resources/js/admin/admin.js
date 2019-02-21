/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './menu';
// import './dropzone';
import './fontawsome';
import VueRouter from 'vue-router';
import App from './views/App';
import Home from './views/Home';
import UsersIndex from './views/UsersIndex';
import Buefy from 'buefy';
import 'buefy/dist/buefy.css';
import AlbumsIndex from '../../../modules/Album/Resources/assets/js/components/AlbumsIndex';
import VueAxios from 'vue-axios';
import axios from 'axios';
import AlbumsShow from '../../../modules/Album/Resources/assets/js/components/AlbumsShow';
import AlbumsCreate from '../../../modules/Album/Resources/assets/js/components/AlbumsCreate';
import AlbumsEdit from '../../../modules/Album/Resources/assets/js/components/AlbumsEdit';

window.Vue = require('vue');

// window._ = require('lodash');

Vue.use(Buefy);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
Vue.use(VueAxios, axios);

// window.axios = require('axios');

Vue.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    Vue.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin-spa/',
            name: 'home',
            component: Home,
        },
        {
            path: '/admin-spa/albums',
            name: 'albums.index',
            component: AlbumsIndex,
        },
        {
            path: '/admin-spa/create',
            name: 'albums.create',
            component: AlbumsCreate,
        },
        {
            path: '/admin-spa/:slug',
            name: 'albums.show',
            component: AlbumsShow,
        },
        {
            path: '/admin-spa/:slug/edit',
            name: 'albums.edit',
            component: AlbumsEdit,
        },
        {
            path: '/admin-spa/users',
            name: 'users.index',
            component: UsersIndex,
        },
    ],
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('passport-clients', require('../components/passport/Clients.vue').default);
//
// Vue.component(
//     'passport-authorized-clients',
//     require('../components/passport/AuthorizedClients.vue').default
// );
//
// Vue.component(
//     'passport-personal-access-tokens',
//     require('../components/passport/PersonalAccessTokens.vue').default
// );

// Vue.component(
//     'albums',
//     require('../../../modules/Album/Resources/assets/js/components/AlbumsIndex.vue').default
// );

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });

let deleteAlbumPictureBtn = document.getElementsByClassName('delete-album-picture');
Array.from(deleteAlbumPictureBtn).forEach(function(el) {
    el.addEventListener('click', event => {
        deleteAlbumPicture(el.parentNode);
    });
});

function deleteAlbumPicture(element) {
    return axios
        .delete(`/api/admin/album-pictures/${element.dataset.albumSlug}`, {
            data: {
                media_id: element.dataset.pictureId,
            },
        })
        .then(res => {
            element.parentNode.removeChild(element);
        })
        .catch(err => {
            alert('Something want wrong.');
            throw err;
        });
}
