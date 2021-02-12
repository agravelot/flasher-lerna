import {
  connectHighlight,
  connectHits,
  connectSearchBox,
  connectStateResults,
} from "react-instantsearch-dom";
import { StateResultsProvided } from "react-instantsearch-core";
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
            className="ais-SearchBox-input w-full text-white bg-transparent focus:outline-none text-4xl md:text-6xl font-bold"
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
              className="bg-gradient-to-r from-blue-700 to-red-700 mb- shadow-none bg-clip-text text-transparent"
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

export const AlbumHits = connectHits(({ hits }) => (
  <ol className="flex flex-wrap md:-mx-3">
    {hits.map((hit) => (
      <div
        className="w-full lg:w-1/2 xl:w-1/3 my-4 items-center p-2"
        key={hit.id}
      >
        <Link
          href={{ pathname: "/albums/[slug]", query: { slug: hit.slug } }}
          prefetch={false}
        >
          <a>
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
          </a>
        </Link>
        <Link
          href={{ pathname: "/albums/[slug]", query: { slug: hit.slug } }}
          prefetch={false}
        >
          <a className="p-4">
            <h3 className="font-bold text-xl mb-2">
              <CustomHighlight hit={hit} attribute="title" />
            </h3>
            <p className="hidden md:block text-gray-200">
              {hit.meta_description}
            </p>
          </a>
        </Link>
      </div>
    ))}
  </ol>
));

export const CategoryHits = connectHits(({ hits }) => (
  <ol className="flex flex-col md:-mx-3">
    {hits.map((hit) => (
      <li className="flex my-4" key={hit.id}>
        <Link
          href={{ pathname: "/categories/[slug]", query: { slug: hit.slug } }}
          prefetch={false}
        >
          <a className="">
            <Avatar name={hit.title} src={hit.cover} />
          </a>
        </Link>
        <div className="p-4">
          <Link
            href={{ pathname: "/categories/[slug]", query: { slug: hit.slug } }}
            prefetch={false}
          >
            <a className="font-bold text-xl mb-2">
              <h3>
                <CustomHighlight hit={hit} attribute="name" />
              </h3>
            </a>
          </Link>
        </div>
      </li>
    ))}
  </ol>
));

export const CosplayerHits = connectHits(({ hits }) => (
  <ol>
    {hits.map((hit) => (
      <div className="flex my-4" key={hit.id}>
        <Link
          href={{ pathname: "/cosplayers/[slug]", query: { slug: hit.slug } }}
          prefetch={false}
        >
          <Avatar name={hit.name} src={hit.avatar} />
        </Link>
        <div className="p-4">
          <Link
            href={{ pathname: "/cosplayers/[slug]", query: { slug: hit.slug } }}
            prefetch={false}
          >
            <a className="font-bold text-xl mb-2">
              <h3>
                <CustomHighlight hit={hit} attribute="name" />
              </h3>
            </a>
          </Link>
        </div>
      </div>
    ))}
  </ol>
));

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
