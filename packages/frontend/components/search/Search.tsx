import { InstantSearch, Index } from "react-instantsearch-dom";
import algoliasearch from "algoliasearch/lite";
import {
  AlbumHits,
  IndexResults,
  CustomSearchBox,
  CosplayerHits,
  CategoryHits,
} from "./Hits";
import { FunctionComponent } from "react";
import { configuration } from "../../utils/configuration";

const Search: FunctionComponent = () => {
  const client = algoliasearch(
    configuration.algolia?.appId ?? "",
    configuration.algolia?.apiKey ?? ""
  );

  return (
    <InstantSearch searchClient={client} indexName="albums-production">
      <CustomSearchBox />
      <span className="inline-block h-1 w-full rounded bg-gradient-to-r from-blue-700 to-red-700 mb-4" />

      <div className="text-white text-2xl">Albums</div>
      <span className="mx-auto inline-block h-1 w-8 rounded bg-gradient-to-r from-blue-700 to-red-700 mb-4" />
      <Index indexName="albums-production">
        <IndexResults>
          <AlbumHits />
        </IndexResults>
      </Index>

      <div className="text-white text-2xl">Catégories</div>
      <span className="mx-auto inline-block h-1 w-8 rounded bg-gradient-to-r from-blue-700 to-red-700 mb-4" />
      <Index indexName="categories-production">
        <IndexResults>
          <CategoryHits />
        </IndexResults>
      </Index>

      <div className="text-white text-2xl">Cosplayers</div>
      <span className="mx-auto inline-block h-1 w-8 rounded bg-gradient-to-r from-blue-700 to-red-700 mb-4" />
      <Index indexName="cosplayers-production">
        <IndexResults>
          <CosplayerHits />
        </IndexResults>
      </Index>
    </InstantSearch>
  );
};

export default Search;
