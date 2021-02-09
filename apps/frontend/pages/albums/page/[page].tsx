import {
  GetStaticPaths,
  GetStaticPathsResult,
  GetStaticProps,
  GetStaticPropsResult,
  NextPage,
} from "next";
import Layout from "../../../components/Layout";
import Header from "../../../components/Header";
import AlbumList from "../../../components/album/AlbumList";
import Pagination, { PaginationProps } from "../../../components/Pagination";
import { getGlobalProps, GlobalProps } from "../../../stores";
import dynamic from "next/dynamic";
import { NextSeo } from "next-seo";
import { configuration } from "../../../utils/configuration";
import { useRouter } from "next/dist/client/router";
import { Album } from "@flasher/models";
import { api, PaginatedReponse, useAuthentication } from "@flasher/common";
import { range } from "../../../utils/util";

type Props = {
  albums: Album[];
  pagination: PaginationProps;
} & GlobalProps;

const perPage = 12;

const DynamicAdminOverlay = dynamic(
  () => import("../../../components/AdminOverlay"),
  {
    ssr: false,
  }
);

const IndexAlbum: NextPage<Props> = ({
  albums,
  pagination,
  socialMedias,
  appName,
}: Props) => {
  const { isAdmin } = useAuthentication();
  const { pathname } = useRouter();

  const title = `Albums page ${pagination.currentPage} - Photographe | ${appName}`;
  const description =
    "Venez plonger dans mes différents univers à travers des albums divers et variés ! Entre balade, cosplay, et portrait.";

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={title}
        description={description}
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
      <Header title="Découvrez mon univers">
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
      <div className="container mx-auto mb-20">
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
      </div>
      {isAdmin && <DynamicAdminOverlay path="/albums" />}
    </Layout>
  );
};

export default IndexAlbum;

export const getStaticProps: GetStaticProps = async ({
  params,
}): Promise<GetStaticPropsResult<Props>> => {
  const body = await api<PaginatedReponse<Album[]>>(
    `/albums?page=${params?.page ?? 1}&per_page=${perPage}`
  ).then((res) => res.json());

  const global = await getGlobalProps();

  return {
    props: {
      ...global,
      albums: body.data,
      pagination: {
        perPage: body.meta.per_page,
        from: body.meta.from,
        to: body.meta.to,
        totalItems: body.meta.total,
        lastPage: body.meta.last_page,
        currentPage: body.meta.current_page,
        showInfo: true,
        routeName: "/albums/page/[page]",
      },
    },
  };
};

export const getStaticPaths: GetStaticPaths = async (): Promise<
  GetStaticPathsResult<{ page: string }>
> => {
  const res = await api<PaginatedReponse<Album[]>>(
    `/albums?per_page=${perPage}`
  ).then((res) => res.json());

  return {
    paths: range(1, res.meta.last_page).map((page) => ({
      params: { page: String(page) },
    })),
    fallback: false,
  };
};
