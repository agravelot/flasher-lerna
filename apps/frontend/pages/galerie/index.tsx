import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import Layout from "../../components/Layout";
import Header from "../../components/Header";
import AlbumList from "../../components/album/AlbumList";
import { getGlobalProps, GlobalProps } from "../../stores";
import dynamic from "next/dynamic";
import { NextSeo } from "next-seo";
import { configuration } from "../../utils/configuration";
import { useRouter } from "next/dist/client/router";
import { Album } from "@flasher/models";
import { api, PaginatedReponse } from "@flasher/common";
import { useAuthentication } from "hooks/useAuthentication";
import { useInView } from "react-cool-inview";
import { useEffect, useState } from "react";
import { Breadcrumb } from "components/Breadcrumb";

type Props = {
  albums: Album[];
  hasNextPage: boolean;
} & GlobalProps;

const perPage = 12;

const DynamicAdminOverlay = dynamic(
  () => import("../../components/AdminOverlay"),
  {
    ssr: false,
  }
);

const IndexAlbum: NextPage<Props> = ({
  albums: initialAlbums,
  hasNextPage: initialHasNextPage,
  socialMedias,
  appName,
}: Props) => {
  const { isAdmin } = useAuthentication();
  const { pathname, asPath } = useRouter();

  const title = `Galerie - Photographe | ${appName}`;
  const description =
    "Venez plonger dans mes différents univers à travers des albums divers et variés ! Entre balade, cosplay, et portrait.";

  const [currentPage, setCurrentPage] = useState(1);
  const [hasNextPage, setHasNextPage] = useState(initialHasNextPage);
  const [albums, setAlbums] = useState(initialAlbums);

  const { observe, unobserve } = useInView({
    // For better UX, we can grow the root margin so the data will be loaded earlier
    rootMargin: "50px 0px",
    onEnter: () => {
      setCurrentPage((cp) => cp + 1);
    },
  });

  useEffect(() => {
    if (currentPage === 1) {
      return;
    }
    const url = getApiUrl(currentPage, perPage);
    api<PaginatedReponse<Album[]>>(url)
      .then((res) => res.json())
      .then((res) => {
        setAlbums((previous) => [...previous, ...res.data]);
        setCurrentPage(currentPage);
        setHasNextPage(currentPage < res.meta.last_page);
      });
  }, [currentPage]);

  useEffect(() => {
    if (!hasNextPage) {
      unobserve();
    }
  }, [hasNextPage, unobserve]);

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={title}
        description={description}
        canonical={`${configuration.appUrl}${asPath}`}
        openGraph={{
          title: title,
          description: description,
          url: configuration.appUrl + pathname,
          images: albums
            .map((a) => ({
              url: a.media?.url ?? "",
              width: a.media?.width,
              height: a.media?.height,
              alt: a.title,
            }))
            .slice(0, 5),
        }}
      />
      <Header
        title="Découvrez mon univers"
        breadcrumb={
          <Breadcrumb
            levels={[
              { name: "Acceuil", path: "/" },
              { name: "Galerie ", path: "/galerie" },
            ]}
          />
        }
      >
        <h2 className="mt-4 text-lg text-gray-300">
          {
            "Venez plonger dans mes différents univers à travers des albums divers et variés ! Entre balade, cosplay, et portrait."
          }
        </h2>
        <p className="mt-4 text-lg text-gray-300">
          {
            "Trouvez votre bonheur, et pourquoi pas, faire partie de cette aventure ?"
          }
        </p>
      </Header>
      <AlbumList albums={albums} />
      {hasNextPage && <span ref={observe} />}
      {isAdmin && <DynamicAdminOverlay path="/galerie" />}
      <div className="pb-16"></div>
    </Layout>
  );
};

export default IndexAlbum;

const getApiUrl = (page: number, perPage: number) =>
  `/albums?page=${page}&limit=${perPage}`;

export const getStaticProps: GetStaticProps = async (): Promise<
  GetStaticPropsResult<Props>
> => {
  const res = await api<PaginatedReponse<Album[]>>(getApiUrl(1, perPage)).then(
    (res) => res.json()
  );

  const global = await getGlobalProps();

  return {
    props: {
      ...global,
      albums: res.data,
      hasNextPage: res.meta.current_page < res.meta.last_page,
    },
    revalidate: 60,
  };
};
