import { useAuthentication } from "hooks/useAuthentication";
import Link from "next/link";
import { FC, useState } from "react";
import { configuration } from "../utils/configuration";
import { Transition, Menu } from "@headlessui/react";
import {
  LoginIcon,
  LogoutIcon,
  PhotographIcon,
  UserIcon,
  AdjustmentsIcon,
} from "@heroicons/react/outline";

const NavbarAccount: FC = () => {
  const { initialized, keycloak, register, login, parsedToken, isAdmin } =
    useAuthentication();
  const { administration } = configuration;

  // Dropdown
  const toggleDropdown = () => setIsOpenned((openned) => !openned);
  const [isOpenned, setIsOpenned] = useState(false);
  const openCloseIndicatorClass = (): string =>
    isOpenned ? "rotate-180" : "rotate-0";

  if (initialized === false || keycloak.authenticated === false) {
    return (
      <ul className="flex items-center" role="menubar">
        <li className="flex items-center" role="menuitem">
          <button
            className="text-white hover:text-gray-300 text-xs font-bold uppercase rounded p-2"
            type="button"
            tabIndex={0}
            onClick={() => register()}
          >
            {"S'inscrire"}
          </button>
        </li>
        <li className="hidden md:flex items-center" role="menuitem">
          <button
            className="inline-flex text-white hover:text-gray-300 text-xs font-bold uppercase rounded p-2"
            type="button"
            tabIndex={0}
            onClick={() => login()}
          >
            <LoginIcon className="w-5 h-5 mx-2" aria-hidden="true" />
            Se connecter
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
            <Menu>
              <Menu.Button>
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
              </Menu.Button>
              <Transition
                show={isOpenned}
                className="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg"
                enter="transition ease-out duration-100"
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-100"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
              >
                <Menu.Items
                  className={
                    " rounded-md bg-white ring-1 ring-black ring-opacity-5"
                  }
                >
                  {isAdmin && (
                    <Menu.Item>
                      <div className="py-1 text-center">
                        <a
                          className="inline-flex justify-start w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                          role="menuitem"
                          tabIndex={0}
                          href={administration}
                          target="_blank"
                          rel="noreferrer"
                        >
                          <AdjustmentsIcon
                            className="w-5 h-5 ml-2 mr-4"
                            aria-hidden="true"
                          />
                          Administration
                        </a>
                      </div>
                    </Menu.Item>
                  )}
                  <Menu.Item>
                    <a
                      className="inline-flex justify-start w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                      role="menuitem"
                      href={keycloak.createAccountUrl()}
                      target="_blank"
                      rel="noreferrer"
                      tabIndex={0}
                    >
                      <UserIcon
                        className="w-5 h-5 ml-2 mr-4"
                        aria-hidden="true"
                      />
                      Mon compte
                    </a>
                  </Menu.Item>
                  <Menu.Item>
                    <Link
                      href={{
                        pathname: "/me/albums",
                      }}
                    >
                      <a
                        role="menuitem"
                        tabIndex={0}
                        className="inline-flex justify-start w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                      >
                        <PhotographIcon
                          className="w-5 h-5 ml-2 mr-4"
                          aria-hidden="true"
                        />
                        Ma galerie
                      </a>
                    </Link>
                  </Menu.Item>
                  <div className="border-t border-gray-100"></div>
                  <Menu.Item>
                    <div className="py-1 text-center">
                      <a
                        tabIndex={0}
                        className="inline-flex justify-start w-full px-4 py-2 text-sm leading-5 text-red-700 hover:bg-gray-100 hover:text-red-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                        role="menuitem"
                        href={keycloak.createLogoutUrl()}
                      >
                        <LogoutIcon
                          className="w-5 h-5 ml-2 mr-4"
                          aria-hidden="true"
                        />
                        Déconnexion
                      </a>
                    </div>
                  </Menu.Item>
                </Menu.Items>
              </Transition>
            </Menu>
          </div>
        </div>
      )}
    </>
  );
};

export default NavbarAccount;
