import Link from "next/link";
import { FunctionComponent, useContext, useState } from "react";
import NavbarAccount from "./NavbarAccount";
import { SearchContext } from "../contexts/AppContext";
import { useAuthentication } from "hooks/useAuthentication";
import {
  MagnifyingGlassIcon,
  Bars3Icon,
  XMarkIcon,
  ArrowLeftOnRectangleIcon,
} from "@heroicons/react/24/outline";
import { Transition } from "@headlessui/react";

const Navbar: FunctionComponent = () => {
  const [showMenu, setShowMenu] = useState(false);
  const toggleNavbar = () => setShowMenu((prev) => !prev);
  const { keycloak, initialized, isAuthenticated } = useAuthentication();
  const context = useContext(SearchContext);
  if (!context) {
    throw new Error("Unable to get context");
  }
  const { open } = context;

  return (
    <nav
      aria-label="Barre de navigation"
      className="navbar-expand-lg absolute top-0 z-10 flex w-full items-center justify-between px-2 py-3"
    >
      <div className="container mx-auto flex flex-wrap items-center justify-between">
        <ul
          role="menubar"
          className="relative flex w-full justify-between lg:static lg:justify-start"
        >
          {/* Left */}
          <li className="flex items-center" role="menuitem">
            <Link
              href={{ pathname: "/" }}
              tabIndex={0}
              className="whitespace-no-wrap mr-4 inline-block p-2 text-sm font-bold uppercase leading-relaxed text-white"
            >
              JKanda
            </Link>
          </li>
          <li className="hidden items-center lg:flex" role="menuitem">
            <Link
              href={{ pathname: "/galerie" }}
              tabIndex={0}
              className="px-3 py-4 text-xs font-bold uppercase text-white hover:text-gray-300"
            >
              Galerie
            </Link>
          </li>
          <li className="hidden items-center lg:flex" role="menuitem">
            <Link
              href={{ pathname: "/categories/page/[page]", query: { page: 1 } }}
              tabIndex={0}
              className="px-3 py-4 text-xs font-bold uppercase text-white hover:text-gray-300"
            >
              Catégories
            </Link>
          </li>
          <li className="hidden items-center lg:flex" role="menuitem">
            <Link
              href={{ pathname: "/cosplayers/page/[page]", query: { page: 1 } }}
              tabIndex={0}
              className="px-3 py-4 text-xs font-bold uppercase text-white hover:text-gray-300"
            >
              Modèles
            </Link>
          </li>
          <li className="hidden items-center lg:flex" role="menuitem">
            <Link
              href={{ pathname: "/", hash: "contact" }}
              tabIndex={0}
              className="px-3 py-4 text-xs font-bold uppercase text-white hover:text-gray-300"
            >
              Contact
            </Link>
          </li>
          <li className="hidden items-center lg:flex" role="menuitem">
            <Link
              href={{ pathname: "/blog" }}
              tabIndex={0}
              className="px-3 py-4 text-xs font-bold uppercase text-white hover:text-gray-300"
            >
              Blog
            </Link>
          </li>
          <li className="hidden items-center lg:flex" role="menuitem">
            <button
              tabIndex={0}
              className="inline-flex px-3 py-4 text-xs font-bold uppercase text-white hover:text-gray-300"
              onClick={() => open()}
            >
              <MagnifyingGlassIcon
                className="mx-2 h-5 w-5"
                aria-hidden="true"
              />
              Rechercher
            </button>
          </li>

          {/* Right */}
          <li className="ml-auto flex items-center" role="menuitem">
            <NavbarAccount />
          </li>
          {/* Mobile */}
          <li className="flex items-center">
            <button
              className="block cursor-pointer p-2 text-xl leading-none lg:hidden"
              type="button"
              aria-label="Ouvrir menu déroulant"
              onClick={() => toggleNavbar()}
            >
              {showMenu ? (
                <XMarkIcon className="h-6 w-6  text-white" aria-hidden="true" />
              ) : (
                <Bars3Icon className="h-6 w-6  text-white" aria-hidden="true" />
              )}
            </button>
          </li>
        </ul>
        <Transition
          show={showMenu}
          className="flex-grow items-center rounded bg-white lg:flex lg:bg-transparent"
          enter="transition ease-out duration-100"
          enterFrom="transform opacity-0 scale-95"
          enterTo="transform opacity-100 scale-100"
          leave="transition ease-in duration-100"
          leaveFrom="transform opacity-100 scale-100"
          leaveTo="transform opacity-0 scale-95"
        >
          <div aria-haspopup="menu" aria-expanded={showMenu}>
            <ul className="mr-auto flex list-none flex-col lg:hidden">
              <li>
                <Link
                  href={{ pathname: "/galerie" }}
                  role="menuitem"
                  tabIndex={0}
                  className="flex w-full items-center justify-center px-3 py-4 text-xs font-bold uppercase text-gray-800"
                >
                  Galerie
                </Link>
              </li>
              <li>
                <Link
                  href={{
                    pathname: "/categories/page/1",
                    query: { page: 1 },
                  }}
                  tabIndex={0}
                  role="menuitem"
                  className="flex w-full items-center justify-center px-3 py-4 text-xs font-bold uppercase text-gray-800"
                >
                  Catégories
                </Link>
              </li>
              <li>
                <Link
                  href={{
                    pathname: "/cosplayers/page/1",
                    query: { page: 1 },
                  }}
                  tabIndex={0}
                  role="menuitem"
                  className="flex w-full items-center justify-center px-3 py-4 text-xs font-bold uppercase text-gray-800"
                >
                  Modèles
                </Link>
              </li>
              <li>
                <Link
                  href={{ pathname: "/", hash: "contact" }}
                  tabIndex={0}
                  role="menuitem"
                  className="flex w-full items-center justify-center px-3 py-4 text-xs font-bold uppercase text-gray-800"
                >
                  Contact
                </Link>
              </li>
              <li>
                <Link
                  href={{ pathname: "/blog" }}
                  tabIndex={0}
                  role="menuitem"
                  className="flex w-full items-center justify-center px-3 py-4 text-xs font-bold uppercase text-gray-800"
                >
                  Blog
                </Link>
              </li>
              <li>
                <button
                  tabIndex={0}
                  role="menuitem"
                  className="flex w-full items-center justify-center px-3 py-4 text-xs font-bold uppercase text-gray-800"
                  onClick={() => open()}
                >
                  <MagnifyingGlassIcon
                    className="mx-2 h-5 w-5"
                    aria-hidden="true"
                  />
                  Rechercher
                </button>
              </li>

              {initialized && isAuthenticated === false && (
                <li>
                  <button
                    tabIndex={0}
                    role="menuitem"
                    className="flex w-full items-center justify-center px-3 py-4 text-xs font-bold uppercase text-gray-800"
                    onClick={() => keycloak?.login()}
                  >
                    <ArrowLeftOnRectangleIcon
                      className="mx-2 h-5 w-5"
                      aria-hidden="true"
                    />
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
