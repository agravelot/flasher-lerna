import Link from "next/link";
import { FC, useState } from "react";
import { configuration } from "../utils/configuration";
import { Transition, Menu } from "@headlessui/react";
import {
  ArrowLeftOnRectangleIcon,
  ArrowRightOnRectangleIcon,
  PhotoIcon,
  UserIcon,
  AdjustmentsVerticalIcon,
} from "@heroicons/react/24/outline";
import { useSession, signIn, signOut } from "next-auth/react";

const NavbarAccount: FC = () => {
  // const { initialized, keycloak, parsedToken, isAdmin, isAuthenticated } =
  //   useAuthentication();
  const { administration } = configuration;
  const session = useSession();

  // Dropdown
  const toggleDropdown = () => setIsOpenned((openned) => !openned);
  const [isOpenned, setIsOpenned] = useState(false);
  const openCloseIndicatorClass = (): string =>
    isOpenned ? "rotate-180" : "rotate-0";

  if (session.status !== "authenticated") {
    return (
      <ul className="flex items-center" role="menubar">
        <li className="flex items-center" role="menuitem">
          {/*<button*/}
          {/*  className="rounded p-2 text-xs font-bold uppercase text-white hover:text-gray-300"*/}
          {/*  type="button"*/}
          {/*  tabIndex={0}*/}
          {/*  onClick={() => keycloak?.register()}*/}
          {/*>*/}
          {/*  {"S'inscrire"}*/}
          {/*</button>*/}
        </li>
        <li className="hidden items-center md:flex" role="menuitem">
          <a
            className="inline-flex rounded p-2 text-xs font-bold uppercase text-white hover:text-gray-300"
            type="button"
            tabIndex={0}
            href={"/api/auth/signin"}
            onClick={(e) => {
              e.preventDefault();
              return signIn("keycloak");
            }}
          >
            <ArrowLeftOnRectangleIcon
              className="mx-2 h-5 w-5"
              aria-hidden="true"
            />
            Se connecter
          </a>
        </li>
      </ul>
    );
  }

  return (
    <>
      {session.status === "authenticated" && (
        <div className="flex items-center">
          <div className="relative inline-block text-left">
            <Menu>
              <Menu.Button>
                <div>
                  <span
                    className="ml-3 inline-flex rounded-md px-4 py-2 text-xs font-bold uppercase text-white shadow-sm hover:shadow-md lg:mr-1"
                    id="options-menu"
                    aria-haspopup="true"
                    aria-expanded="true"
                    onClick={() => toggleDropdown()}
                  >
                    {session.data.user?.name}
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
                  </span>
                </div>
              </Menu.Button>
              <Transition
                show={isOpenned}
                className="absolute right-0 mt-2 w-56 origin-top-right rounded-md shadow-lg"
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
                  {session.data.user?.role === "admin" && (
                    <Menu.Item>
                      <div className="py-1 text-center">
                        <a
                          className="inline-flex w-full justify-start px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 focus:outline-none"
                          role="menuitem"
                          tabIndex={0}
                          href={administration}
                          target="_blank"
                          rel="noreferrer"
                        >
                          <AdjustmentsVerticalIcon
                            className="ml-2 mr-4 h-5 w-5"
                            aria-hidden="true"
                          />
                          Administration
                        </a>
                      </div>
                    </Menu.Item>
                  )}
                  <Menu.Item>
                    <a
                      className="inline-flex w-full justify-start px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 focus:outline-none"
                      role="menuitem"
                      // href={keycloak?.createAccountUrl()}
                      target="_blank"
                      rel="noreferrer"
                      tabIndex={0}
                    >
                      <UserIcon
                        className="ml-2 mr-4 h-5 w-5"
                        aria-hidden="true"
                      />
                      Mon compte
                    </a>
                  </Menu.Item>
                  <Menu.Item>
                    <span>
                      <Link
                        href={{
                          pathname: "/me/albums",
                        }}
                        role="menuitem"
                        tabIndex={0}
                        className="inline-flex w-full justify-start px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:bg-gray-100 focus:text-gray-900 focus:outline-none"
                      >
                        <PhotoIcon
                          className="ml-2 mr-4 h-5 w-5"
                          aria-hidden="true"
                        />
                        Ma galerie
                      </Link>
                    </span>
                  </Menu.Item>
                  <div className="border-t border-gray-100"></div>
                  <Menu.Item>
                    <div className="py-1 text-center">
                      <a
                        tabIndex={0}
                        className="inline-flex w-full justify-start px-4 py-2 text-sm leading-5 text-red-700 hover:bg-gray-100 hover:text-red-900 focus:bg-gray-100 focus:text-gray-900 focus:outline-none"
                        role="menuitem"
                        href={"/api/auth/signout"}
                        onClick={(e) => {
                          e.preventDefault();
                          return signOut();
                        }}
                      >
                        <ArrowRightOnRectangleIcon
                          className="ml-2 mr-4 h-5 w-5"
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
