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
