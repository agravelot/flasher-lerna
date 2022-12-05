import { FunctionComponent } from "react";
import Link from "next/link";
import { range } from "../utils/util";

export class PaginationProps {
  showInfo!: boolean;
  totalItems!: number;
  currentPage!: number;
  perPage!: number;
  from!: number;
  to!: number;
  lastPage!: number;
  routeName!: string;
}

const Pagination: FunctionComponent<PaginationProps> = ({
  showInfo,
  currentPage,
  routeName,
  lastPage,
  from,
  to,
  totalItems,
}: PaginationProps) => (
  <div className="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
    <div className="flex flex-1 justify-between sm:hidden">
      <Link
        href={{
          pathname: routeName,
          query: { page: currentPage > 1 ? currentPage - 1 : 1 },
        }}
        prefetch={false}
        tabIndex={0}
        aria-label="Précédent"
        rel="prev"
        className="focus:ring-blue relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none active:bg-gray-100 active:text-gray-700"
      >
        Précédent
      </Link>
      <Link
        href={{
          pathname: routeName,
          query: { page: currentPage < lastPage ? currentPage + 1 : lastPage },
        }}
        prefetch={false}
        tabIndex={0}
        aria-label="Suivant"
        rel="next"
        className="focus:ring-blue relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:border-blue-300 focus:outline-none active:bg-gray-100 active:text-gray-700"
      >
        Suivant
      </Link>
    </div>
    <div className="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      {showInfo && (
        <div>
          <span className="text-sm leading-5 text-gray-700">
            {"Affichage de "}
            <span className="font-medium">{from}</span>
            {" à "}
            <span className="font-medium">{to}</span>
            {" sur "}
            <span className="font-medium">{totalItems}</span>
            {" résultats"}
          </span>
        </div>
      )}
      <div>
        <nav className="relative z-0 inline-flex shadow-sm">
          <Link
            href={{
              pathname: routeName,
              query: { page: currentPage > 1 ? currentPage - 1 : 1 },
            }}
            prefetch={false}
            tabIndex={0}
            aria-label="Précédent"
            rel="prev"
            className="focus:ring-blue relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none active:bg-gray-100 active:text-gray-500"
          >
            <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path
                fillRule="evenodd"
                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                clipRule="evenodd"
              />
            </svg>
          </Link>
          {range(1, lastPage).map((page) => (
            <Link
              v-for="page in Array.from(Array(lastPage).keys()).map((x) => ++x)"
              key={page}
              href={{ pathname: routeName, query: { page: page } }}
              prefetch={false}
              tabIndex={0}
              className="focus:ring-blue relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-none active:bg-gray-100 active:text-gray-700"
            >
              {page}
            </Link>
          ))}

          <Link
            href={{
              pathname: routeName,
              query: {
                page: currentPage < lastPage ? currentPage + 1 : lastPage,
              },
            }}
            prefetch={false}
            aria-label="Suivant"
            rel="next"
            tabIndex={0}
            className="focus:ring-blue relative -ml-px inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-none active:bg-gray-100 active:text-gray-500"
          >
            <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path
                fillRule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clipRule="evenodd"
              />
            </svg>
          </Link>
        </nav>
      </div>
    </div>
  </div>
);

export default Pagination;
