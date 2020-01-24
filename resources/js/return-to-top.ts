//Get the button:
const returnToTopButton: HTMLElement = document.getElementById("return-to-top");

returnToTopButton.addEventListener('click', () => {
    window.scrollTo({top: 0, behavior: 'smooth'});
});

async function scrollFunction(): Promise<void> {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        console.log('show');
        returnToTopButton.style.visibility = "visible";
        returnToTopButton.style.opacity = "1";
    } else {
        console.log('hide');
        returnToTopButton.style.visibility = "hidden";
        returnToTopButton.style.opacity = "0";
    }
}

window.onscroll = async (): Promise<void> => scrollFunction();
