import Link from "next/link";
import { FunctionComponent, useContext, useState } from "react";
import useAuthentication from "hooks/useAuthentication";
import NavbarAccount from "~/components/NavbarAccount";
import { SearchContext } from "contexts/AppContext";

const Navbar: FunctionComponent = () => {
  const [showMenu, setShowMenu] = useState(false);
  const toggleNavbar = () => setShowMenu((prev) => !prev);
  const { login, keycloak, initialized } = useAuthentication();
  const context = useContext(SearchContext);
  if (!context) {
    throw new Error("Unable to get context");
  }
  const { open } = context;

  return (
    <nav
      aria-label="Barre de navigation"
      className="top-0 absolute z-10 w-full flex items-center justify-between px-2 py-3 navbar-expand-lg"
    >
      <div className="container mx-auto flex flex-wrap items-center justify-between">
        <ul
          role="menubar"
          className="w-full relative flex justify-between lg:static lg:justify-start"
        >
          {/* Left */}
          <li className="flex items-center">
            <Link href={{ pathname: "/" }}>
              <a
                role="menuitem"
                tabIndex={0}
                className="text-sm font-bold leading-relaxed inline-block mr-4 whitespace-no-wrap uppercase text-white p-2"
              >
                JKanda
              </a>
            </Link>
          </li>
          <li className="hidden lg:flex items-center">
            <Link
              href={{ pathname: "/albums/page/[page]", query: { page: 1 } }}
            >
              <a
                role="menuitem"
                tabIndex={0}
                className="text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              >
                Albums
              </a>
            </Link>
          </li>
          <li className="hidden lg:flex items-center">
            <Link
              href={{ pathname: "/categories/page/[page]", query: { page: 1 } }}
            >
              <a
                role="menuitem"
                tabIndex={0}
                className="text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              >
                Catégories
              </a>
            </Link>
          </li>
          <li className="hidden lg:flex items-center">
            <Link
              href={{ pathname: "/cosplayers/page/[page]", query: { page: 1 } }}
            >
              <a
                role="menuitem"
                tabIndex={0}
                className="text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              >
                Cosplayers
              </a>
            </Link>
          </li>
          <li className="hidden lg:flex items-center">
            <button
              role="menuitem"
              tabIndex={0}
              className="text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              onClick={() => open()}
            >
              Rechercher
            </button>
          </li>
          <li className="hidden lg:flex items-center">
            <Link href={{ pathname: "/", hash: "contact" }}>
              <a
                role="menuitem"
                tabIndex={0}
                className="text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              >
                Contact
              </a>
            </Link>
          </li>

          {/* Right */}
          <li className="flex items-center ml-auto">
            <NavbarAccount />
          </li>
          {/* Mobile */}
          <li className="flex items-center">
            <button
              className="cursor-pointer text-xl leading-none p-2 block lg:hidden"
              type="button"
              aria-label="Ouvrir menu déroulant"
              onClick={() => toggleNavbar()}
            >
              {!showMenu && (
                <svg
                  className="w-6 h-6 text-white"
                  fill="none"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
              )}
              {showMenu && (
                <svg
                  className="w-6 h-6 text-white"
                  fill="none"
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth="2"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              )}
            </button>
          </li>
        </ul>

        {/* <transition
            enter-active-className="transition ease-out duration-100"
            enter-className="transform opacity-0 scale-95"
            enter-to-className="transform opacity-100 scale-100"
            leave-active-className="transition ease-in duration-75"
            leave-className="transform opacity-100 scale-100"
            leave-to-className="transform opacity-0 scale-95"
          > */}
        {showMenu && (
          <div
            aria-expanded={showMenu ? true : false}
            className="lg:flex flex-grow items-center bg-white lg:bg-transparent rounded"
          >
            <ul className="flex flex-col lg:flex-row list-none mr-auto">
              <li>
                <Link
                  href={{ pathname: "/albums/page/[page]", query: { page: 1 } }}
                >
                  <a
                    aria-haspopup="true"
                    role="menuitem"
                    tabIndex={0}
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    <div>Albums</div>
                  </a>
                </Link>
              </li>
              <li>
                <Link
                  href={{ pathname: "/categories/page/1", query: { page: 1 } }}
                >
                  <a
                    aria-haspopup="true"
                    tabIndex={0}
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    <span>Categories</span>
                  </a>
                </Link>
              </li>
              <li>
                <Link
                  href={{ pathname: "/cosplayers/page/1", query: { page: 1 } }}
                >
                  <a
                    tabIndex={0}
                    aria-haspopup="true"
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    <span>Cosplayers</span>
                  </a>
                </Link>
              </li>
              <li>
                <Link href={{ pathname: "/", hash: "contact" }}>
                  <a
                    tabIndex={0}
                    aria-haspopup="true"
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    <span>Contact</span>
                  </a>
                </Link>
              </li>
              <li>
                <button
                  tabIndex={0}
                  aria-haspopup="true"
                  role="menuitem"
                  className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  onClick={() => open()}
                >
                  Rechercher
                </button>
              </li>

              {initialized && keycloak.authenticated === false && (
                <li>
                  <button
                    aria-haspopup="true"
                    tabIndex={0}
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                    onClick={() => login()}
                  >
                    <i className="lg:text-gray-300 text-gray-500 fab fa-github text-lg leading-lg"></i>
                    <span>Se connecter</span>
                  </button>
                </li>
              )}
            </ul>
          </div>
        )}
        {/* </transition> */}
      </div>
    </nav>
  );
};

export default Navbar;
