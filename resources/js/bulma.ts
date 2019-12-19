import { debounce } from 'lodash-es';

// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', () => {
  const resizeResponsiveMedias = (): void => {
    // onload="this.onload=null;this.sizes=Math.ceil(this.getBoundingClientRect().width/window.innerWidth*100)+'vw';console.log(this.sizes);"
    const responsiveMedias: HTMLCollectionOf<Element> = document.getElementsByClassName(
      'responsive-media'
    );

    Array.from(responsiveMedias).forEach((el: Element) => {
      const computedSize: number = Math.ceil(
        (el.getBoundingClientRect().width / window.innerWidth) * 100
      );
      // Avoid to set 0vw and load full sized image
      if (computedSize !== 0) {
        (el as HTMLImageElement).sizes = `${computedSize}vw`;
      }
    });
  };

  resizeResponsiveMedias();

  window.onresize = debounce(resizeResponsiveMedias, 500);

  // Navbar
  const navbarBurgers = document.getElementsByClassName('navbar-burger');

  Array.from(navbarBurgers).forEach((el: Element) => {
    el.addEventListener('click', () => {
      // Get the target from the "data-target" attribute
      const dataSetTarget: string | undefined = (el as HTMLCanvasElement).dataset.target;
      if (dataSetTarget !== undefined) {
        const target: HTMLElement | null = document.getElementById(dataSetTarget);
        // Toggle the class on both the "navbar-burger" and the "navbar-menu"
        el.classList.toggle('is-active');
                target?.classList.toggle('is-active');
      }
    });
  });

  // Notificaitons
  (Array.from(document.getElementsByClassName('.notification .delete'))).forEach(($delete) => {
    $delete.addEventListener('click', () => {
            $delete.parentNode?.parentNode?.removeChild($delete?.parentNode);
    });
  });
});
