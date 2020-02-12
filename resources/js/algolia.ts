import algoliasearch from "algoliasearch";
import autocomplete from "autocomplete.js";

const client = algoliasearch("0JPH2CFGR5", "4e7a32c3a5cdaf7c7899c1e116392b05");
const albums = client.initIndex("albums-production");
const cosplayers = client.initIndex("cosplayers-production");
const categories = client.initIndex("categories-production");

const loadThumb = window.screen.width > 600;

const suggestion = (suggestion): string => {
    const result = `<span>${suggestion._highlightResult.title?.value ??
        suggestion._highlightResult.name?.value ??
        ""}</span>`;

    if (!loadThumb) {
        return result;
    }

    const thumb = suggestion.thumb
        ? `<img loading="lazy" src="${suggestion.thumb}" alt="${suggestion.title}"/>`
        : "";
    return `<figure class="image is-hidden-mobile">
                ${thumb}
            </figure>${result}`;
};
const empty = (query): string => {
    return (
        '<span class="aa-empty">Aucun résultat pour "' +
        query.query +
        '"</span>'
    );
};

const getHeader = (name: string): string =>
    `<div class="aa-suggestions-category">${name}</div>`;

autocomplete(
    "#aa-search-input",
    {
        keyboardShortcuts: ["s", "/"],
        autoselect: true,
        debug: false,
        templates: {
            footer:
                '<div class="is-pulled-right has-padding-right-sm">' +
                '<img class="image is-16x16" ' +
                'loading="lazy"' +
                'src="https://www.algolia.com/gatsby-images/shared/algolia_logo/algolia-blue-mark.svg" ' +
                'alt="Algolia logo"/></div>'
        }
    },
    [
        {
            source: autocomplete.sources.hits(albums, { hitsPerPage: 3 }),
            displayKey: "title",
            templates: {
                header: getHeader("Albums"),
                suggestion,
                empty
            }
        },
        {
            source: autocomplete.sources.hits(cosplayers, { hitsPerPage: 3 }),
            displayKey: "name",
            templates: {
                header: getHeader("Cosplayers"),
                suggestion,
                empty
            }
        },
        {
            source: autocomplete.sources.hits(categories, { hitsPerPage: 3 }),
            displayKey: "name",
            templates: {
                header: getHeader("Catégories"),
                suggestion,
                empty
            }
        }
    ]
)
    .on("autocomplete:opened", () => {
        ga("send", {
            hitType: "event",
            eventCategory: "Search",
            eventAction: "Opened"
        });
    })
    .on("autocomplete:selected", (event, suggestion) => {
        ga("send", {
            hitType: "event",
            eventCategory: "Search",
            eventAction: "Clicked",
            eventLabel: suggestion.url
        });

        // Do nothing on click, as the browser will already do it
        // if (context.selectionMethod === 'click') {
        //     return;
        // }
        // Change the page, for example, on other events
        window.location.assign(suggestion.url);
    });

const searchInput = document.getElementsByClassName("aa-input-search");
const elementsToHideOnSearch = document.getElementsByClassName(
    "is-hidden-on-search"
);

Array.from(searchInput || []).forEach((input: HTMLElement) => {
    input.addEventListener("focus", () => {
        Array.from(elementsToHideOnSearch || []).forEach((el: HTMLElement) => {
            el.style.display = "none";
        });
    });
    input.addEventListener("blur", () => {
        Array.from(elementsToHideOnSearch || []).forEach((el: HTMLElement) => {
            el.style.display = "block";
        });
    });
});
