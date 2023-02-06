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

interface Props {
  googleSearch: string;
}

const Search: FunctionComponent<Props> = ({googleSearch}) => {
  const client = algoliasearch(
    configuration.algolia?.appId ?? "",
    configuration.algolia?.apiKey ?? ""
  );

  return (
    <InstantSearch searchClient={client} indexName="albums-production">
      <CustomSearchBox defaultRefinement={googleSearch}/>
      <span className="mb-4 inline-block h-1 w-full rounded bg-gradient-to-r from-blue-700 to-red-700" />

      <div className="text-2xl text-white">Albums</div>
      <span className="mx-auto mb-4 inline-block h-1 w-8 rounded bg-gradient-to-r from-blue-700 to-red-700" />
      <Index indexName="albums-production">
        <IndexResults>
          <AlbumHits />
        </IndexResults>
      </Index>

      <div className="text-2xl text-white">Catégories</div>
      <span className="mx-auto mb-4 inline-block h-1 w-8 rounded bg-gradient-to-r from-blue-700 to-red-700" />
      <Index indexName="categories-production">
        <IndexResults>
          <CategoryHits />
        </IndexResults>
      </Index>

      <div className="text-2xl text-white">Modèles</div>
      <span className="mx-auto mb-4 inline-block h-1 w-8 rounded bg-gradient-to-r from-blue-700 to-red-700" />
      <Index indexName="cosplayers-production">
        <IndexResults>
          <CosplayerHits />
        </IndexResults>
      </Index>
    </InstantSearch>
  );
};

export default Search;
