const elements = document.getElementsByClassName("download-album");

Array.from(elements || []).forEach((e: HTMLElement) => {
    e.addEventListener("click", async event => {
        e.classList.add("is-loading");
        ga("send", {
            hitType: "event",
            eventCategory: "Albums",
            eventAction: "Download",
            eventLabel:
                event.target instanceof HTMLAnchorElement
                    ? event.target.href
                    : "Unable to get link"
        });
        e.addEventListener("blur", () => {
            e.classList.contains("is-loading") &&
                e.classList.remove("is-loading");
        });
    });
});
