import {
  GetStaticPaths,
  GetStaticPathsResult,
  GetStaticProps,
  GetStaticPropsResult,
  NextPage,
} from "next";
import Header from "../../../components/Header";
import Pagination from "../../../components/Pagination";
import Layout from "../../../components/Layout";
import CategoryList from "../../../components/category/CategoryList";
import { range } from "../../../utils/util";
import { PaginationProps } from "../../../components/Pagination";
import { getGlobalProps, GlobalProps } from "../../../stores";
import dynamic from "next/dynamic";
import { NextSeo } from "next-seo";
import { Category } from "@flasher/models";
import { api, PaginatedReponse } from "@flasher/common";
import { useAuthentication } from "hooks/useAuthentication";
import { configuration } from "utils/configuration";
import { useRouter } from "next/dist/client/router";
import { Breadcrumb } from "components/Breadcrumb";
import { removeQueryParams } from "../../../utils/canonical";

type Props = {
  categories: Category[];
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
  categories,
  pagination,
  socialMedias,
  appName,
}: Props) => {
  const { isAdmin } = useAuthentication();
  const { asPath } = useRouter();

  const title = `Catégories page ${pagination.currentPage} - Photographe | ${appName}`;
  const description =
    "Besoin de s’évader dans un domaine précis ? Venez naviguer à travers les albums grâce aux catégories.";

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={title}
        description={description}
        canonical={`${configuration.appUrl}${removeQueryParams(asPath)}`}
        openGraph={{
          title: title,
          description,
          images: categories
            .filter((c) => c.cover !== null)
            .map((c) => ({
              url: c.cover?.url ?? "",
              width: c.cover?.width,
              height: c.cover?.height,
              alt: c.name,
            })),
        }}
      />
      <Header
        title={"Par thématique"}
        description={description}
        breadcrumb={
          <Breadcrumb
            levels={[
              { name: "Acceuil", path: "/" },
              { name: "Catégories", path: "/categories" },
              {
                name: `Page ${pagination.currentPage}`,
                path: `/categories/page/${pagination.currentPage}`,
              },
            ]}
          />
        }
      />
      <CategoryList categories={categories} />
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
      {isAdmin && <DynamicAdminOverlay path="/categories" />}
    </Layout>
  );
};

export default IndexAlbum;

export const getStaticProps: GetStaticProps = async (
  context
): Promise<GetStaticPropsResult<Props>> => {
  const body = await api<PaginatedReponse<Category[]>>(
    `/categories?page=${context.params?.page ?? 1}&per_page=${perPage}`
  ).then((res) => res.json());

  if (body.data.length === 0) {
    return { notFound: true, revalidate: 60 };
  }

  const global = await getGlobalProps();

  return {
    props: {
      ...global,
      categories: body.data,
      pagination: {
        perPage: body.meta.per_page,
        from: body.meta.from,
        to: body.meta.to,
        totalItems: body.meta.total,
        lastPage: body.meta.last_page,
        currentPage: body.meta.current_page,
        showInfo: true,
        routeName: "/categories/page/[page]",
      },
    },
    revalidate: 60,
  };
};

export const getStaticPaths: GetStaticPaths = async (): Promise<
  GetStaticPathsResult<{ page: string }>
> => {
  const res = await api<PaginatedReponse<Category[]>>(
    `/categories?per_page=${perPage}`
  ).then((res) => res.json());

  return {
    paths: range(1, res.meta.last_page).map((page) => ({
      params: { page: String(page) },
    })),
    fallback: "blocking",
  };
};
