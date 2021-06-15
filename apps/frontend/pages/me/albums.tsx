import { GetStaticProps } from "next";
import React, { FunctionComponent, useEffect, useState } from "react";
import Layout from "../../components/Layout";
import Header from "../../components/Header";
import Link from "next/link";
import Pagination, { PaginationProps } from "../../components/Pagination";
import { getGlobalProps, GlobalProps } from "../../stores";
import MyAlbumItem from "../../components/album/MyAlbumItem";
import { NextSeo } from "next-seo";
import { api, PaginatedReponse, useAuthentication } from "@flasher/common";
import { Album } from "@flasher/models";

type Props = GlobalProps;

export enum State {
  Loading = "loading",
  Completed = "completed",
  Failed = "failed",
}

// TODO Notify errors list/download
const MyAlbums: FunctionComponent<Props> = ({
  socialMedias,
  appName,
}: Props) => {
  const [albums, setAlbums] = useState<Album[]>([]);
  const [status, setStatus] = useState<State>(State.Loading);
  const [pagination, setPagination] = useState<PaginationProps>();

  const { initialized, keycloak } = useAuthentication();

  useEffect(() => {
    if (initialized === false) {
      return;
    }

    if (!keycloak?.authenticated) {
      keycloak.login();
    }

    api<PaginatedReponse<Album[]>>("/me/albums", {
      headers: {
        Authorization: `Bearer ${keycloak?.token}`,
      },
    })
      .then((res) => {
        {
          if (!res.response.ok) {
            setStatus(State.Failed);
            throw new Error("Unable to get albums");
          }
          return res.json();
        }
      })
      .then((json) => {
        setPagination({
          perPage: json.meta.per_page,
          from: json.meta.from,
          to: json.meta.to,
          totalItems: json.meta.total,
          lastPage: json.meta.last_page,
          currentPage: json.meta.current_page,
          showInfo: true,
          routeName: "/me/albums/page/[page]",
        });
        return json.data;
      })
      .then((data) => {
        setStatus(State.Completed);
        setAlbums(data);
      });
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [initialized]);

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`Mes albums | ${appName}`}
        description="Découvrir les albums dans lesquels j'apparais"
        openGraph={{
          title: `Mes albums | ${appName}`,
          description: "Découvrir les albums dans lesquels j'apparais",
        }}
      />
      <Header title="Mes albums" />
      {albums.length > 0 && (
        <div>
          <div className="container mx-auto py-8">
            <div className="flex flex-wrap -mx-2">
              {albums.map((album) => (
                <div key={album.id} className="w-full p-1 flex-auto md:w-1/2">
                  <MyAlbumItem showDownload={true} album={album} />
                </div>
              ))}
            </div>
          </div>
          <div className="container mx-auto mb-12">
            {pagination && (
              <Pagination
                showInfo={pagination.showInfo}
                totalItems={pagination.totalItems}
                currentPage={pagination.currentPage}
                perPage={pagination.perPage}
                from={pagination.from}
                to={pagination.to}
                lastPage={pagination.lastPage}
                routeName={pagination.routeName}
              />
            )}
          </div>
        </div>
      )}

      {status !== State.Loading && albums.length === 0 && (
        <div className="mb-32">
          <section className="container mx-auto">
            <img src="/photo.svg" alt="" className="h-64 w-64 mx-auto" />
            <div className="text-center">
              <span className="font-bold text-black">
                {status === State.Completed &&
                  "Vous n'avez aucun album pour le moment."}
                {status === State.Failed && "Une erreur a eu lieu."}
              </span>
              <br />
              {/*  eslint-disable-next-line prettier/prettier */}
              Pour remédier à cela, n&apos;hésitez à me <Link
                href={{
                  pathname: "/",
                  hash: "contact",
                }}
              >
                <a className="underline text-pink-600 hover:text-pink-800">
                  contacter
                </a>
                {/*  eslint-disable-next-line prettier/prettier */}
              </Link> !
              <br />
              Je me ferais un plaisir à vous répondre.
            </div>
          </section>
        </div>
      )}

      {status === State.Loading && (
        <div className="container mx-auto py-8 p-64">
          <div className="mb-32">
            <section className="container mx-auto">
              <img src="/photo.svg" alt="" className="h-64 w-64 mx-auto" />
              <div className="text-center">
                <span className="font-bold text-black">Chargement...</span>
              </div>
            </section>
          </div>
        </div>
      )}
    </Layout>
  );
};

export default MyAlbums;

export const getStaticProps: GetStaticProps = async () => {
  const global = await getGlobalProps();

  return { props: { ...global } };
};
