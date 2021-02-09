import { useAuthentication } from "@flasher/common";
import Link from "next/link";
import { FunctionComponent, useState } from "react";
import { configuration } from "../utils/configuration";

const NavbarAccount: FunctionComponent = () => {
  const {
    initialized,
    keycloak,
    register,
    login,
    parsedToken,
    isAdmin,
  } = useAuthentication();
  const { administration } = configuration;

  // Dropdown
  const toggleDropdown = () => setIsOpenned((openned) => !openned);
  const [isOpenned, setIsOpenned] = useState(false);
  const openCloseIndicatorClass = (): string =>
    isOpenned ? "rotate-180" : "rotate-0";

  if (initialized === false || keycloak.authenticated === false) {
    return (
      <ul className="flex items-center">
        <li className="flex items-center">
          <button
            className="text-white hover:text-gray-300 text-xs font-bold uppercase rounded p-2"
            type="button"
            tabIndex={0}
            onClick={() => register()}
          >
            {"S'inscrire"}
          </button>
        </li>
        <li className="hidden md:flex items-center">
          <button
            className="text-white hover:text-gray-300 text-xs font-bold uppercase rounded p-2"
            type="button"
            tabIndex={0}
            onClick={() => login()}
          >
            {"Se connecter"}
          </button>
        </li>
      </ul>
    );
  }

  return (
    <>
      {initialized && keycloak.authenticated && (
        <div className="flex items-center">
          <div className="relative inline-block text-left">
            <div>
              <span className="rounded-md shadow-sm">
                <button
                  id="options-menu"
                  type="button"
                  className="inline-flex text-white text-xs font-bold uppercase px-4 py-2 shadow hover:shadow-md lg:mr-1 ml-3"
                  aria-haspopup="true"
                  aria-expanded="true"
                  onClick={() => toggleDropdown()}
                >
                  {parsedToken?.preferred_username}
                  <svg
                    className={`-mr-1 ml-2 h-5 w-5 transform transition-transform duration-150 ${openCloseIndicatorClass()}`}
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fillRule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clipRule="evenodd"
                    />
                  </svg>
                </button>
              </span>
            </div>
            {/* <transition
							enter-active-className="transition ease-out duration-100"
							enter-className="transform opacity-0 scale-95"
							enter-to-className="transform opacity-100 scale-100"
							leave-active-className="transition ease-in duration-75"
							leave-className="transform opacity-100 scale-100"
							leave-to-className="transform opacity-0 scale-95"
						> */}
            {isOpenned && (
              <ul className="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                <li
                  className="rounded-md bg-white ring-1 ring-black ring-opacity-5"
                  role="menu"
                  aria-orientation="vertical"
                  aria-labelledby="options-menu"
                >
                  {isAdmin && (
                    <div className="py-1 text-center">
                      <a
                        className="w-full block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                        role="menuitem"
                        tabIndex={0}
                        href={administration}
                        target="_blank"
                        rel="noreferrer"
                      >
                        Administration
                      </a>
                    </div>
                  )}
                  <div className="border-t border-gray-100"></div>

                  <div className="py-1 text-center">
                    <a
                      className="w-full block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                      role="menuitem"
                      href={keycloak.createAccountUrl()}
                      target="_blank"
                      rel="noreferrer"
                      tabIndex={0}
                    >
                      Mon compte
                    </a>
                    <Link
                      href={{
                        pathname: "/me/albums",
                      }}
                    >
                      <a
                        role="menuitem"
                        tabIndex={0}
                        className="w-full block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                      >
                        Mes albums
                      </a>
                    </Link>
                  </div>

                  <div className="border-t border-gray-100"></div>
                  <div className="py-1 text-center">
                    <a
                      tabIndex={0}
                      className="w-full block px-4 py-2 text-sm leading-5 text-red-700 hover:bg-gray-100 hover:text-red-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                      role="menuitem"
                      href={keycloak.createLogoutUrl()}
                    >
                      Déconnexion
                    </a>
                  </div>
                </li>
              </ul>
            )}
            {/* </transition> */}
          </div>
        </div>
      )}

      {/* Dropdown panel, show/hide based on dropdown state.

        Entering: "transition ease-out duration-100"
          From: "transform opacity-0 scale-95"
          To: "transform opacity-100 scale-100"
        Leaving: "transition ease-in duration-75"
          From: "transform opacity-100 scale-100"
          To: "transform opacity-0 scale-95"

      */}
    </>
  );
};

export default NavbarAccount;
