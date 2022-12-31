import { GetStaticProps } from "next";
import React, { FunctionComponent, useEffect, useState } from "react";
import Layout from "../../components/Layout";
import Header from "../../components/Header";
import Image from "next/image";
import Link from "next/link";
import Pagination, { PaginationProps } from "../../components/Pagination";
import { getGlobalProps, GlobalProps } from "../../stores";
import MyAlbumItem from "../../components/album/MyAlbumItem";
import { NextSeo } from "next-seo";
import { api, PaginatedReponse } from "@flasher/common";
import { Album } from "@flasher/models";
import { useAuthentication } from "hooks/useAuthentication";
import { configuration } from "utils/configuration";
import { useRouter } from "next/dist/client/router";
import pictureLogo from "../../public/photo.svg";
import { removeQueryParams } from "../../utils/canonical";

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
  const { asPath } = useRouter();

  useEffect(() => {
    if (!initialized) {
      return;
    }

    if (!keycloak?.authenticated) {
      keycloak?.login();
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

  const pictureLogoComponent = (
    <Image
      className="mx-auto h-64 w-64"
      src={pictureLogo}
      height={264}
      width={264}
      alt="logo image"
    />
  );

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`Ma galerie | ${appName}`}
        description="Découvrir les albums dans lesquels j'apparais"
        canonical={`${configuration.appUrl}${removeQueryParams(asPath)}`}
        openGraph={{
          title: `Ma galerie | ${appName}`,
          description: "Découvrir les albums dans lesquels j'apparais",
        }}
      />
      <Header title="Ma galerie" />
      {albums.length > 0 && (
        <div>
          <div className="container mx-auto py-8">
            <div className="-mx-2 flex flex-wrap">
              {albums.map((album) => (
                <div key={album.id} className="flex w-full p-1 md:w-1/2">
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
            <div className="text-center">{pictureLogoComponent}</div>
            <div className="text-center">
              <span className="font-bold text-black">
                {status === State.Completed &&
                  "Vous n'avez aucun album pour le moment."}
                {status === State.Failed && "Impossible de charger vos images."}
              </span>
              <br />
              {/*  eslint-disable-next-line prettier/prettier */}
              Pour remédier à cela, n&apos;hésitez à me{" "}
              <Link
                href={{
                  pathname: "/",
                  hash: "contact",
                }}
                className="text-pink-600 underline hover:text-pink-800"
              >
                contacter
                {/*  eslint-disable-next-line prettier/prettier */}
              </Link>{" "}
              !
              <br />
              Je me ferais un plaisir à vous répondre.
            </div>
          </section>
        </div>
      )}

      {status === State.Loading && (
        <section className="container mx-auto p-64 py-8">
          <div className="mb-32">
            <div className="text-center">{pictureLogoComponent}</div>
            <div className="text-center">
              <span className="font-bold text-black">Chargement...</span>
            </div>
          </div>
        </section>
      )}
    </Layout>
  );
};

export default MyAlbums;

export const getStaticProps: GetStaticProps = async () => {
  const global = await getGlobalProps();

  return { props: { ...global } };
};
