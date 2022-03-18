import Link from "next/link";
import { FunctionComponent, useContext, useState } from "react";
import NavbarAccount from "./NavbarAccount";
import { SearchContext } from "../contexts/AppContext";
import { useAuthentication } from "hooks/useAuthentication";
import {
  SearchIcon,
  MenuIcon,
  XIcon,
  LoginIcon,
} from "@heroicons/react/outline";
import { Transition } from "@headlessui/react";

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
          <li className="flex items-center" role="menuitem">
            <Link href={{ pathname: "/" }}>
              <a
                tabIndex={0}
                className="text-sm font-bold leading-relaxed inline-block mr-4 whitespace-no-wrap uppercase text-white p-2"
              >
                JKanda
              </a>
            </Link>
          </li>
          <li className="hidden lg:flex items-center">
            <Link href={{ pathname: "/galerie" }}>
              <a
                role="menuitem"
                tabIndex={0}
                className="text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              >
                Galerie
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
          <li className="hidden lg:flex items-center">
            <Link href={{ pathname: "/blog" }}>
              <a
                role="menuitem"
                tabIndex={0}
                className="text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              >
                Blog
              </a>
            </Link>
          </li>
          <li className="hidden lg:flex items-center">
            <button
              role="menuitem"
              tabIndex={0}
              className="inline-flex text-white hover:text-gray-300 px-3 py-4 text-xs uppercase font-bold"
              onClick={() => open()}
            >
              <SearchIcon className="w-5 h-5 mx-2" aria-hidden="true" />
              Rechercher
            </button>
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
              {showMenu ? (
                <XIcon className="w-6 h-6  text-white" aria-hidden="true" />
              ) : (
                <MenuIcon className="w-6 h-6  text-white" aria-hidden="true" />
              )}
            </button>
          </li>
        </ul>
        <Transition
          show={showMenu}
          className="lg:flex flex-grow items-center bg-white lg:bg-transparent rounded"
          enter="transition ease-out duration-100"
          enterFrom="transform opacity-0 scale-95"
          enterTo="transform opacity-100 scale-100"
          leave="transition ease-in duration-100"
          leaveFrom="transform opacity-100 scale-100"
          leaveTo="transform opacity-0 scale-95"
        >
          <div aria-haspopup="menu" aria-expanded={showMenu}>
            <ul className="flex flex-col lg:flex-row list-none mr-auto">
              <li>
                <Link href={{ pathname: "/galerie" }}>
                  <a
                    role="menuitem"
                    tabIndex={0}
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    Albums
                  </a>
                </Link>
              </li>
              <li>
                <Link
                  href={{
                    pathname: "/categories/page/1",
                    query: { page: 1 },
                  }}
                >
                  <a
                    tabIndex={0}
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    Categories
                  </a>
                </Link>
              </li>
              <li>
                <Link
                  href={{
                    pathname: "/cosplayers/page/1",
                    query: { page: 1 },
                  }}
                >
                  <a
                    tabIndex={0}
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    Cosplayers
                  </a>
                </Link>
              </li>
              <li>
                <Link href={{ pathname: "/", hash: "contact" }}>
                  <a
                    tabIndex={0}
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    Contact
                  </a>
                </Link>
              </li>
              <li>
                <Link href={{ pathname: "/blog" }}>
                  <a
                    tabIndex={0}
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  >
                    Blog
                  </a>
                </Link>
              </li>
              <li>
                <button
                  tabIndex={0}
                  role="menuitem"
                  className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                  onClick={() => open()}
                >
                  <SearchIcon className="w-5 h-5 mx-2" aria-hidden="true" />
                  Rechercher
                </button>
              </li>

              {initialized && keycloak.authenticated === false && (
                <li>
                  <button
                    tabIndex={0}
                    role="menuitem"
                    className="text-gray-800 px-3 py-4 flex items-center justify-center text-xs uppercase font-bold w-full"
                    onClick={() => login()}
                  >
                    <LoginIcon className="w-5 h-5 mx-2" aria-hidden="true" />
                    Se connecter
                  </button>
                </li>
              )}
            </ul>
          </div>
        </Transition>
      </div>
    </nav>
  );
};

export default Navbar;
