import { GetStaticPaths, GetStaticProps, NextPage } from "next";
import Link from "next/link";
import CosplayerItem from "../../components/cosplayer/CosplayerItem";
import AlbumList from "../../components/album/AlbumList";
import { NextSeo, ArticleJsonLd } from "next-seo";
import { useRouter } from "next/dist/client/router";
import { configuration } from "../../utils/configuration";
import { range } from "../../utils/util";
import { getGlobalProps, GlobalProps } from "../../stores";
import { useState } from "react";
import dynamic from "next/dynamic";
import { Album } from "@flasher/models";
import {
  api,
  HttpNotFound,
  PaginatedReponse,
  useAuthentication,
  WrappedResponse,
} from "@flasher/common";
import Layout from "../../components/Layout";
import Header from "../../components/Header";
import AlbumMediaList from "../../components/album/AlbumMediaList";

type Props = {
  album: Album;
  recommendedAlbums: Album[];
} & GlobalProps;

const DynamicFullscreenCarousel = dynamic(
  () => import("../../components/FullscreenCarousel"),
  { ssr: false }
);

const DynamicAdminOverlay = dynamic(
  () => import("../../components/AdminOverlay"),
  {
    ssr: false,
  }
);

const ShowAlbum: NextPage<Props> = ({
  album,
  recommendedAlbums,
  socialMedias,
  appName,
}: Props) => {
  const [loadCarousel, setLoadCarousel] = useState<boolean>(false);
  const [isCarouselOpenned, setIsCarouselOpenned] = useState<boolean>(true);
  const [carouselIndex, setCarouselIndex] = useState<number>(0);

  const router = useRouter();
  const url = `${configuration.appUrl}${router.pathname}`;

  const openGalleryAt = (index: number) => {
    setCarouselIndex(index);
    setLoadCarousel(true);
    setIsCarouselOpenned(true);
  };

  const close = () => setIsCarouselOpenned(false);

  const { isAdmin } = useAuthentication();

  const title = `${album.title} - Photographe | ${appName}`;

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={title}
        description={album.meta_description}
        additionalMetaTags={[{ name: "author", content: appName }]}
        openGraph={{
          title: title,
          description: album.meta_description,
          type: "article",
          images: album.medias?.slice(0, 5).flatMap((m) => ({
            url: m.url,
            alt: m.name,
            width: m.width,
            height: m.height,
          })),
          article: {
            publishedTime: album.published_at ?? "",
            modifiedTime: album.updated_at ?? album.published_at ?? "",
            authors: [appName],
            section: "Photography",
            tags: album.categories?.map((category) => category.name),
          },
        }}
      />
      <ArticleJsonLd
        url={url}
        title={title}
        images={album.medias?.slice(0, 5).flatMap((m) => m.url) ?? []}
        datePublished={album.created_at}
        dateModified={album.updated_at ?? album.created_at}
        authorName={appName}
        publisherLogo={"/icon-512x512.png"} // TODO
        publisherName={appName}
        description={album.meta_description}
      />
      <Header
        title={album.title}
        src={album.medias?.[0].url}
        alt-description={album.title}
      >
        <div className="px-6 py-4">
          {album.categories?.map((category) => (
            <Link
              href={{
                pathname: "/categories/[slug]",
                query: { slug: category.slug },
              }}
              key={category.id}
            >
              <a tabIndex={0}>
                <span className="inline-block rounded-full px-3 py-1 text-sm text-white m-1">
                  {category.name}
                </span>
              </a>
            </Link>
          ))}
        </div>
      </Header>

      {loadCarousel && album.medias && (
        <DynamicFullscreenCarousel
          medias={album.medias}
          beginAt={carouselIndex}
          openned={isCarouselOpenned}
          close={close}
        />
      )}

      <div className="container mx-auto">
        <div className="flex justify-center py-16 px-4 text-justify">
          <article
            className="content-center prose max-w-none"
            dangerouslySetInnerHTML={{ __html: album.body ?? "" }}
          />
        </div>
      </div>

      <div
        className="container mx-auto py-4 overflow-hidden mb-16"
        // :style="galleryContentVisibility"
      >
        <AlbumMediaList album={album} openGalleryAt={openGalleryAt} />
      </div>

      {album.cosplayers && (
        <div className="container mx-auto py-4 overflow-hidden">
          <h2 className="text-3xl text-center my-8 font-semibold">
            {album.cosplayers?.length === 1 ? "Modèle" : "Modèles"}
          </h2>
          <div className="flex flex-wrap items-center justify-center">
            {album.cosplayers?.map((cosplayer) => (
              <div
                className="w-1/2 lg:w-1/3 flex justify-center"
                key={cosplayer.id}
              >
                <CosplayerItem cosplayer={cosplayer} />
              </div>
            ))}
          </div>
        </div>
      )}

      {recommendedAlbums && (
        <div className="container mx-auto mb-16 lg:mb-24">
          <h2 className="text-3xl text-center my-8 font-semibold">
            Découvrez en plus
          </h2>
          <div className="md:flex flex-wrap md:-mx-3">
            <AlbumList albums={recommendedAlbums} />
          </div>
        </div>
      )}

      {isAdmin && <DynamicAdminOverlay />}
    </Layout>
  );
};

export default ShowAlbum;

export const getStaticProps: GetStaticProps = async ({ params }) => {
  try {
    const album = await api<WrappedResponse<Album>>(`/albums/${params?.slug}`)
      .then((res) => res.json())
      .then((res) => res.data);

    const albumCategories = album.categories
      ?.map((c) => c.id)
      .flat()
      .join(",");

    const recommendedAlbums = await api<PaginatedReponse<Album[]>>(
      `/albums?filter[categories.id]=${albumCategories}`
    )
      .then((res) => res.json())
      .then((json) => json.data)
      .then((albums) => albums.filter((a) => a.id !== album.id).slice(0, 3))
      .catch((e) => {
        // error({ statusCode: 500, message: 'Unable to get recommended albums' })
        throw e;
      });

    const global = await getGlobalProps();

    return { props: { album, recommendedAlbums, ...global }, revalidate: 60 };
  } catch (e) {
    if (e instanceof HttpNotFound) {
      return { notFound: true };
    }
    throw e;
  }
};

export const getStaticPaths: GetStaticPaths = async () => {
  const res = await api<PaginatedReponse<Album[]>>("/albums").then((res) =>
    res.json()
  );

  const albums: Album[][] = await Promise.all(
    range(1, res.meta.last_page).map(async (page) => {
      return await api<PaginatedReponse<Album[]>>(`/albums?page=${page}`)
        .then((res) => res.json())
        .then((res) => res.data);
    })
  );

  return {
    paths: albums.flat().map((album) => ({
      params: { slug: album.slug },
    })),
    fallback: "blocking",
  };
};
