import algoliasearch from 'algoliasearch';
import autocomplete from "autocomplete.js";

const client = algoliasearch('0JPH2CFGR5', '4e7a32c3a5cdaf7c7899c1e116392b05');
const albums = client.initIndex('albums-production');
const cosplayers = client.initIndex('cosplayers-production');
const categories = client.initIndex('categories-production');

const suggestion = (suggestion): string => {
    const thumb = suggestion.thumb ? `<img src="${suggestion.thumb}" alt="${suggestion.title}"/>` : '';
    return `${thumb}<span>${suggestion._highlightResult.title?.value ?? suggestion._highlightResult.name?.value ?? ''}</span>`;
};
const empty = (query): string => {
    return '<span class="aa-empty">Aucun résultat pour "' + query.query +'"</span>';
};

const getHeader = (name: string): string => `<div class="aa-suggestions-category">${name}</div>`;

autocomplete('#aa-search-input', {
    keyboardShortcuts: ['s', '/'],
    debug: true,
}, [
    {
        source: autocomplete.sources.hits(albums, { hitsPerPage: 3 }),
        displayKey: 'title',
        templates: {
            header: getHeader('Albums'),
            suggestion,
            empty
        }
    },
    {
        source: autocomplete.sources.hits(cosplayers, { hitsPerPage: 3 }),
        displayKey: 'name',
        templates: {
            header: getHeader('Cosplayers'),
            suggestion,
            empty
        }
    },
    {
        source: autocomplete.sources.hits(categories, { hitsPerPage: 3 }),
        displayKey: 'name',
        templates: {
            header: getHeader('Catégories'),
            suggestion,
            empty
        }
    }
]).on('autocomplete:selected', function(event, suggestion, dataset, context) {
    // Do nothing on click, as the browser will already do it
    // if (context.selectionMethod === 'click') {
    //     return;
    // }
    // Change the page, for example, on other events
    window.location.assign(suggestion.url);
});
