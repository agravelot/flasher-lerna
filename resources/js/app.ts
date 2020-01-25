/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './sentry';
import './bulma';
import './modal';
import './lazy-load';
import './return-to-top';


const buttons = document.getElementsByClassName('is-share-button');

Array.from(buttons).forEach((button: HTMLButtonElement) => {
    const shareData = {
        title: button.dataset.title,
        text: button.dataset.text,
        url: button.dataset.url,
    };

    // Must be triggered some kind of "user activation"
    button.addEventListener('click', async () => {
        try {
            console.log(shareData);
            // eslint-disable-next-line @typescript-eslint/no-explicit-any
            await (navigator as any).share(shareData);
        } catch (err) {
            console.error(err);
        }
        console.log('MDN shared successfully');
    });
});
