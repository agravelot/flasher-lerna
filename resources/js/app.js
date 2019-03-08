/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './admin/menu';
// import './dropzone';
import './fontawsome';
import VueRouter from 'vue-router';
import Buefy from 'buefy';
import VueAxios from 'vue-axios';
import axios from 'axios';
import App from './admin/views/App';
import Home from './admin/views/Home';
import UsersIndex from './admin/views/UsersIndex';
import '../sass/buefy.scss';
import AlbumsIndex from '../../modules/Album/Resources/assets/js/components/admin/AlbumsIndex';
import AlbumsShow from '../../modules/Album/Resources/assets/js/components/front/AlbumsShow';
import AlbumsCreate from '../../modules/Album/Resources/assets/js/components/admin/AlbumsCreate';
import AlbumsEdit from '../../modules/Album/Resources/assets/js/components/admin/AlbumsEdit';
require('./admin/fontawsome');
require('bulma-modal-fx/src/_js/modal-fx');
window.Vue = require('vue');

// window._ = require('lodash');

window.Vue.use(Buefy);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.Vue.use(VueAxios, axios);

window.Vue.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.Vue.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/admin/spa/',
      name: 'home',
      component: Home,
    },
    {
      path: '/admin/spa/albums',
      name: 'admin.albums.index',
      component: AlbumsIndex,
    },
    {
      path: '/admin/spa/create',
      name: 'admin.albums.create',
      component: AlbumsCreate,
    },
    {
      path: '/admin/spa/:slug',
      name: 'admin.albums.show',
      component: AlbumsShow,
    },
    {
      path: '/admin/spa/:slug/edit',
      name: 'admin.albums.edit',
      component: AlbumsEdit,
    },
    {
      path: '/admin/spa/users',
      name: 'admin.users.index',
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

Vue.component(
    'albums-masonry',
    require('../../modules/Album/Resources/assets/js/components/front/AlbumsMasonry').default
);

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

// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', () => {
// onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';console.log(this.sizes);"
  const responsiveMedias = document.getElementsByClassName('responsive-media');

  Array.from(responsiveMedias).forEach((el) => {
    el.sizes = `${Math.ceil((el.getBoundingClientRect().width / window.innerWidth) * 100)}vw`;
  });

  const navbarBurgers = document.getElementsByClassName('navbar-burger');

  Array.from(navbarBurgers).forEach((el) => {
    el.addEventListener('click', () => {
      // Get the target from the "data-target" attribute
      const target = document.getElementById(el.dataset.target);

      // Toggle the class on both the "navbar-burger" and the "navbar-menu"
      el.classList.toggle('is-active');
      target.classList.toggle('is-active');
    });
  });
});