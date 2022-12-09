import {
  connectHighlight,
  connectHits,
  connectSearchBox,
  connectStateResults,
} from "react-instantsearch-dom";
import { StateResultsProvided } from "react-instantsearch-core";
import { useSearch } from "../../contexts/AppContext";
import Link from "next/link";
import Avatar from "../Avatar";
import { ReactElement } from "react";
import { sizes } from "utils/util";
import Image from "next/image";

export const CustomSearchBox = connectSearchBox(
  ({ currentRefinement, refine }) => {
    return (
      <div className="ais-SearchBox">
        <form className="ais-SearchBox-form" noValidate>
          <input
            className="ais-SearchBox-input w-full bg-transparent text-4xl font-bold text-white focus:outline-none md:text-6xl"
            autoComplete="off"
            autoCorrect="off"
            autoCapitalize="off"
            placeholder="Rechercher"
            spellCheck="false"
            maxLength={512}
            type="search"
            value={currentRefinement}
            onChange={(event) => refine(event.currentTarget.value)}
            autoFocus
          />
          <button
            className="ais-SearchBox-reset"
            type="reset"
            title="Clear the search query."
            hidden
          >
            <svg
              className="ais-SearchBox-resetIcon"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              width="10"
              height="10"
            />
          </button>
          <span className="ais-SearchBox-loadingIndicator" hidden>
            <svg
              width="16"
              height="16"
              viewBox="0 0 38 38"
              xmlns="http://www.w3.org/2000/svg"
              stroke="#444"
              className="ais-SearchBox-loadingIcon"
            />
          </span>
        </form>
      </div>
    );
  }
);

export const CustomHighlight = connectHighlight(
  ({ highlight, attribute, hit }) => {
    const parsedHit = highlight({
      highlightProperty: "_highlightResult",
      attribute,
      hit,
    });

    return (
      <span>
        {parsedHit.map((part, index) =>
          part.isHighlighted ? (
            <em
              className="mb- bg-gradient-to-r from-blue-700 to-red-700 bg-clip-text text-transparent shadow-none"
              style={{
                WebkitBackgroundClip: "text",
              }}
              key={index}
            >
              {part.value}
            </em>
          ) : (
            <span key={index} className="text-white">
              {part.value}
            </span>
          )
        )}
      </span>
    );
  }
);

export const AlbumHits = connectHits(({ hits }) => {
  const { close } = useSearch();
  return (
    <ol className="flex flex-wrap md:-mx-3">
      {hits.map((hit) => (
        <div
          className="my-4 w-full items-center p-2 lg:w-1/2 xl:w-1/3"
          key={hit.id}
        >
          <Link
            href={{ pathname: "/galerie/[slug]", query: { slug: hit.slug } }}
            prefetch={false}
            onClick={() => close()}
          >
            <Image
              className="object-cover"
              src={hit.cover}
              alt={hit.title}
              width={2000}
              height={2000}
              sizes={sizes(3, "container")}
              // quality={95}
              // width={hit.width}
              // height="hit.height"
              // loading="lazy"
            />
          </Link>
          <Link
            href={{ pathname: "/galerie/[slug]", query: { slug: hit.slug } }}
            prefetch={false}
            className="p-4"
            onClick={() => close()}
          >
            <h3 className="mb-2 text-xl font-bold">
              <CustomHighlight hit={hit} attribute="title" />
            </h3>
            <p className="hidden text-gray-200 md:block">
              {hit.meta_description}
            </p>
          </Link>
        </div>
      ))}
    </ol>
  );
});

export const CategoryHits = connectHits(({ hits }) => {
  const { close } = useSearch();
  return (
    <ol className="flex flex-col md:-mx-3">
      {hits.map((hit) => (
        <li className="my-4 flex" key={hit.id}>
          <Link
            href={{ pathname: "/categories/[slug]", query: { slug: hit.slug } }}
            prefetch={false}
            className="mb-2 text-xl font-bold"
            onClick={() => close()}
          >
            <Avatar name={hit.name} src={hit.cover} />
          </Link>
          <div className="p-4">
            <Link
              href={{ pathname: "/categories/[slug]", query: { slug: hit.slug } }}
              prefetch={false}
              className="mb-2 text-xl font-bold"
              onClick={() => close()}
            >
              <h3>
                <CustomHighlight hit={hit} attribute="name" />
              </h3> 
            </Link>
          </div>
        </li>
      ))}
    </ol>
  );
});

export const CosplayerHits = connectHits(({ hits }) => {
  const { close } = useSearch();
  return (
    <ol>
      {hits.map((hit) => (
        <div className="my-4 flex" key={hit.id}>
          <Link
            href={{ pathname: "/cosplayers/[slug]", query: { slug: hit.slug } }}
            prefetch={false}
            passHref={true}
            onClick={() => close()}
          >
            <Avatar name={hit.name} src={hit.avatar} />
          </Link>
          <div className="p-4">
            <Link
              href={{ pathname: "/cosplayers/[slug]", query: { slug: hit.slug } }}
              prefetch={false}
              className="mb-2 text-xl font-bold"
              onClick={() => close()}
            >
              <h3>
                <CustomHighlight hit={hit} attribute="name" />
              </h3>
            </Link>
          </div>
        </div>
      ))}
    </ol>
  );
});

export const IndexResults = connectStateResults(
  ({
    searchState,
    searchResults,
    children,
  }: StateResultsProvided & { children: ReactElement }) => {
    if (searchResults?.nbHits !== 0) {
      return children;
    }

    return (
      <div>
        <div className="text-white">
          <div className="flex justify-center">
            <img
              src="/search-no-results.svg"
              alt="Aucun résultat illustration"
              className="h-48"
            />
          </div>
          <div className="text-center">
            Aucun résultat pour la recherche:
            <q className="font-semibold">{searchState.query}</q>
          </div>
        </div>
      </div>
    );
  }
);
