import { FunctionComponent } from "react";
import SocialMediaItem from "./SocialMedia";
import Link from "next/link";
import Separator from "./Separator";
import { SocialMedia } from "@flasher/models";
import { configuration } from "../utils/configuration";
import { useAuthentication } from "hooks/useAuthentication";

interface Props {
  socialMedias: SocialMedia[];
}

const date = new Date().getFullYear();

const Footer: FunctionComponent<Props> = ({ socialMedias }: Props) => {
  const { keycloak, isAuthenticated } = useAuthentication();
  const { administration } = configuration;

  return (
    <footer className="relative bg-gray-300 pt-8 pb-6">
      <nav aria-label="Pied de page">
        <Separator separatorClass={"text-gray-300"} position={"top"} />
        <div className="container mx-auto px-4">
          <div className="flex flex-wrap">
            <div className="w-full px-4 lg:w-6/12">
              <p className="text-3xl font-semibold">Restons en contact !</p>
              <p className="mt-0 mb-2 text-lg">
                {
                  "Vous pouvez me retrouver sur l'une de ces plateformes, je vous répondrai dans un délai de 24 heures."
                }
              </p>
              <div className="my-6 flex space-x-2">
                {socialMedias &&
                  socialMedias.map((sm) => (
                    <div
                      className="align-center h-10 w-10 items-center justify-center rounded-full bg-white font-normal text-blue-400 shadow-lg"
                      key={sm.id}
                    >
                      <SocialMediaItem socialMedia={sm} />
                    </div>
                  ))}
              </div>
            </div>
            <div className="w-full px-4 lg:w-6/12">
              <div className="items-top mb-6 flex flex-wrap">
                <div className="ml-auto w-full px-4 lg:w-4/12">
                  <span className="mb-2 block text-sm font-semibold uppercase">
                    Liens utiles
                  </span>
                  <ul className="list-unstyled">
                    <li>
                      <Link
                        href="/"
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Page d&apos;accueil
                      </Link>
                    </li>
                    <li>
                      <Link
                        href={{
                          pathname: "/albums/page/[page]",
                          query: { page: 1 },
                        }}
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Albums
                      </Link>
                    </li>
                    <li>
                      <Link
                        href={{
                          pathname: "/categories/page/[page]",
                          query: { page: 1 },
                        }}
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Catégories
                      </Link>
                    </li>
                    <li>
                      <Link
                        href={{
                          pathname: "/cosplayers/page/[page]",
                          query: { page: 1 },
                        }}
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Modèles
                      </Link>
                    </li>
                    <li>
                      <Link
                        href={{
                          pathname: "/blog",
                        }}
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Blog
                      </Link>
                    </li>
                  </ul>
                </div>
                <div className="w-full px-4 lg:w-4/12">
                  <span className="mb-2 block text-sm font-semibold uppercase">
                    Mon compte
                  </span>
                  {/* Logged */}
                  {isAuthenticated && (
                    <ul className="list-unstyled">
                      <li>
                        <a
                          tabIndex={0}
                          className="block py-2 text-sm"
                          href={administration}
                          target="_blank"
                          rel="noreferrer"
                        >
                          Administration
                        </a>
                      </li>

                      <a
                        tabIndex={0}
                        className="block py-2 text-sm"
                        onClick={() => keycloak?.accountManagement}
                        target="_blank"
                        rel="noreferrer"
                      >
                        Mon compte
                      </a>
                      <Link
                        href={{
                          pathname: "/me/albums",
                        }}
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Mes albums
                      </Link>

                      <li className="items-center md:flex">
                        <a
                          tabIndex={0}
                          className="block py-2 text-sm"
                          onClick={() => keycloak?.logout()}
                        >
                          Déconnexion
                        </a>
                      </li>
                    </ul>
                  )}
                  {/* Guest */}
                  {!isAuthenticated && (
                    <ul className="list-unstyled">
                      <li>
                        <button
                          tabIndex={0}
                          className="block py-2 text-sm"
                          type="button"
                          onClick={() => keycloak?.register()}
                        >
                          S&apos;inscrire
                        </button>
                      </li>
                      <li className="items-center md:flex">
                        <button
                          tabIndex={0}
                          className="block py-2 text-sm"
                          type="button"
                          onClick={() => keycloak?.login()}
                        >
                          Se connecter
                        </button>
                      </li>
                    </ul>
                  )}
                </div>
                <div className="w-full px-4 lg:w-4/12">
                  <span className="mb-2 block text-sm font-semibold uppercase">
                    Autres
                  </span>
                  <ul className="list-unstyled">
                    <li>
                      <Link
                        href={{ pathname: "/", hash: "contact" }}
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Me contacter
                      </Link>
                    </li>
                    <li>
                      <Link
                        href={{ pathname: "/mentions-legales" }}
                        tabIndex={0}
                        className="block py-2 text-sm"
                      >
                        Mentions légales
                      </Link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <hr className="my-6 border-gray-400" />
          <div className="flex flex-wrap items-center justify-center md:justify-between">
            <div className="mx-auto w-full px-4 text-center md:w-4/12">
              <div className="py-1 text-sm">
                Copyright © 2018-{date} jkanda.fr, Tous droits réservés
                <br />
                {"Réalisation : "}
                <a
                  href="https://github.com/agravelot"
                  target="_blank"
                  rel="noreferrer"
                >
                  Antoine GRAVELOT
                </a>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </footer>
  );
};

export default Footer;
