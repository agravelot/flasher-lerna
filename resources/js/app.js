/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('albums', require('./components/Albums.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});

// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', function() {

    // onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';console.log(this.sizes);"
    const responsiveMedias = document.getElementsByClassName('responsive-media');

    Array.from(responsiveMedias).forEach(el => {
        el.sizes = Math.ceil((el.getBoundingClientRect().width / window.innerWidth) * 100) + 'vw';
    });

    const navbarBurgers = document.getElementsByClassName('navbar-burger');

    Array.from(navbarBurgers).forEach(el => {
        el.addEventListener('click', function() {
            // Get the target from the "data-target" attribute
            let target = document.getElementById(el.dataset.target);

            // Toggle the class on both the "navbar-burger" and the "navbar-menu"
            el.classList.toggle('is-active');
            target.classList.toggle('is-active');
        });
    });
});

require('./fontawsome');
require('bulma-modal-fx/src/_js/modal-fx');
