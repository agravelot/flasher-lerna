// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', () => {
// onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';console.log(this.sizes);"
    const responsiveMedias: HTMLCollectionOf<Element> = document.getElementsByClassName('responsive-media');

    Array.from(responsiveMedias).forEach((el: Element) => {
        (<HTMLSourceElement> el).sizes = `${Math.ceil((el.getBoundingClientRect().width / window.innerWidth) * 100)}vw`;
    });

    const navbarBurgers = document.getElementsByClassName('navbar-burger');

    Array.from(navbarBurgers).forEach((el: Element) => {
        el.addEventListener('click', () => {
            // Get the target from the "data-target" attribute
            const dataSetTarget: string | undefined = (<HTMLCanvasElement>el).dataset.target;
            const target: "" | HTMLElement | null | undefined = dataSetTarget && document.getElementById(dataSetTarget);

            // Toggle the class on both the "navbar-burger" and the "navbar-menu"
            el.classList.toggle('is-active');
            target && target.classList.toggle('is-active');
        });
    });
});
