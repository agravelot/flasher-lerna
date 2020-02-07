import algoliasearch from 'algoliasearch';
import autocomplete from "autocomplete.js";

const client = algoliasearch('0JPH2CFGR5', '4e7a32c3a5cdaf7c7899c1e116392b05');
const albums = client.initIndex('albums-production');
const cosplayers = client.initIndex('cosplayers-production');
const categories = client.initIndex('categories-production');

const suggestion = (suggestion) => {
    return '<span>' + suggestion._highlightResult.title.value + '</span>';
    //+ '<span><br>' + suggestion._highlightResult.meta_description.value + '</span>';
};
const empty = (query) => {
    return 'Aucun résultat pour "' + query.query +'"';
}

autocomplete('#aa-search-input', {}, [
    {
        source: autocomplete.sources.hits(albums, { hitsPerPage: 3 }),
        displayKey: 'title',
        templates: {
            header: '<div class="aa-suggestions-category">Albums</div>',
            suggestion,
            empty
        }
    },
    {
        source: autocomplete.sources.hits(cosplayers, { hitsPerPage: 3 }),
        displayKey: 'name',
        templates: {
            header: '<div class="aa-suggestions-category">Cosplayers</div>',
            suggestion,
            empty
        }
    },
    {
        source: autocomplete.sources.hits(categories, { hitsPerPage: 3 }),
        displayKey: 'name',
        templates: {
            header: '<div class="aa-suggestions-category">Catégories</div>',
            suggestion,
            empty
        }
    }
]);
