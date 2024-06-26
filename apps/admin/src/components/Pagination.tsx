import { FunctionComponent } from "react";
import { range } from "@flasher/common";
import { Link } from "react-router-dom";
import React from "react";

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

export const Pagination: FunctionComponent<PaginationProps> = ({
  currentPage,
  routeName,
  lastPage,
  from,
  to,
  totalItems,
}: PaginationProps) => (
  <div className="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
    <div className="flex-1 flex justify-between sm:hidden">
      <Link
        to={{
          pathname: routeName,
          search: `?page=${currentPage > 1 ? currentPage - 1 : 1}`,
        }}
      >
        <a
          tabIndex={0}
          aria-label="Précédent"
          rel="prev"
          className="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:ring-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
        >
          Précédent
        </a>
      </Link>
      <Link
        to={{
          pathname: routeName,
          search: `?page=${
            currentPage < lastPage ? currentPage + 1 : lastPage
          }`,
        }}
      >
        <a
          tabIndex={0}
          aria-label="Suivant"
          rel="next"
          className="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:ring-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
        >
          Suivant
        </a>
      </Link>
    </div>
    <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
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
      <div>
        <nav className="relative z-0 inline-flex shadow-sm">
          <Link
            to={{
              pathname: routeName,
              search: `?page=${currentPage > 1 ? currentPage - 1 : 1}`,
            }}
          >
            <a
              tabIndex={0}
              aria-label="Précédent"
              rel="prev"
              className="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
            >
              <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path
                  fillRule="evenodd"
                  d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                  clipRule="evenodd"
                />
              </svg>
            </a>
          </Link>
          {range(1, lastPage).map((page) => (
            <Link
              v-for="page in Array.from(Array(lastPage).keys()).map((x) => ++x)"
              key={page}
              to={{
                pathname: routeName,
                search: `?page=${page}`,
              }}
            >
              <a
                tabIndex={0}
                className="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
              >
                {page}
              </a>
            </Link>
          ))}

          <Link
            to={{
              pathname: routeName,
              search: `?page=${
                currentPage < lastPage ? currentPage + 1 : lastPage
              }`,
            }}
          >
            <a
              aria-label="Suivant"
              rel="next"
              tabIndex={0}
              className="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
            >
              <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path
                  fillRule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clipRule="evenodd"
                />
              </svg>
            </a>
          </Link>
        </nav>
      </div>
    </div>
  </div>
);
