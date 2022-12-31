import {
  GetStaticPaths,
  GetStaticPathsResult,
  GetStaticProps,
  GetStaticPropsResult,
  NextPage,
} from "next";
import Layout from "../../../components/Layout";
import CosplayerList from "../../../components/cosplayer/CosplayerList";
import Header from "../../../components/Header";
import Pagination, { PaginationProps } from "../../../components/Pagination";
import { range } from "../../../utils/util";
import { getGlobalProps, GlobalProps } from "../../../stores";
import dynamic from "next/dynamic";
import { NextSeo } from "next-seo";
import { api, PaginatedReponse } from "@flasher/common";
import { Cosplayer } from "@flasher/models";
import { useAuthentication } from "hooks/useAuthentication";
import { useRouter } from "next/dist/client/router";
import { configuration } from "utils/configuration";
import { removeQueryParams } from "../../../utils/canonical";

type Props = {
  cosplayers: Cosplayer[];
  pagination: PaginationProps;
} & GlobalProps;

const DynamicAdminOverlay = dynamic(
  () => import("../../../components/AdminOverlay"),
  {
    ssr: false,
  }
);

const perPage = 24;

const IndexAlbum: NextPage<Props> = ({
  cosplayers,
  pagination,
  socialMedias,
  appName,
}: Props) => {
  const { isAdmin } = useAuthentication();
  const { asPath } = useRouter();

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`Modèles page ${pagination.currentPage} - Photographe | ${appName}`}
        description="Venez plonger dans mes différents univers à travers des albums divers et variés ! Entre balade, cosplay, et portrait."
        canonical={`${configuration.appUrl}${removeQueryParams(asPath)}`}
        openGraph={{
          title: `Modèles page ${pagination.currentPage} - Photographe | ${appName}`,
          description:
            "Venez plonger dans mes différents univers à travers des albums divers et variés ! Entre balade, cosplay, et portrait.",
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
      <CosplayerList cosplayers={cosplayers} />
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
      {isAdmin && <DynamicAdminOverlay path="/cosplayers" />}
    </Layout>
  );
};

export default IndexAlbum;

export const getStaticProps: GetStaticProps = async ({
  params,
}): Promise<GetStaticPropsResult<Props>> => {
  const body = await api<PaginatedReponse<Cosplayer[]>>(
    `/cosplayers?page=${params?.page}&per_page=${perPage}`
  ).then((res) => res.json());

  if (body.data.length === 0) {
    return { notFound: true, revalidate: 60 };
  }

  const global = await getGlobalProps();

  return {
    props: {
      ...global,
      cosplayers: body.data,
      pagination: {
        perPage: body.meta.per_page,
        from: body.meta.from,
        to: body.meta.to,
        totalItems: body.meta.total,
        lastPage: body.meta.last_page,
        currentPage: body.meta.current_page,
        showInfo: true,
        routeName: "/cosplayers/page/[page]",
      },
    },
    revalidate: 60,
  };
};

export const getStaticPaths: GetStaticPaths = async (): Promise<
  GetStaticPathsResult<{ page: string }>
> => {
  const res = await api<PaginatedReponse<Cosplayer[]>>(
    `/cosplayers?per_page=${perPage}`
  ).then((res) => res.json());

  return {
    paths: range(1, res.meta.last_page).map((page) => ({
      params: { page: String(page) },
    })),
    fallback: "blocking",
  };
};
