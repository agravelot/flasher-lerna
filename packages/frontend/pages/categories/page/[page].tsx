import {
  GetStaticPaths,
  GetStaticPathsResult,
  GetStaticProps,
  GetStaticPropsResult,
  NextPage,
} from "next";
import Header from "~/components/Header";
import Pagination from "~/components/Pagination";
import Layout from "~/components/Layout";
import Category from "~/models/category";
import CategoryList from "~/components/category/CategoryList";
import { api, PaginatedReponse } from "~/utils/api";
import { range } from "~/utils/util";
import { PaginationProps } from "~/components/Pagination";
import { getGlobalProps, GlobalProps } from "~/stores";
import dynamic from "next/dynamic";
import useAuthentication from "~/hooks/useAuthentication";
import { NextSeo } from "next-seo";

type Props = {
  categories: Category[];
  pagination: PaginationProps;
} & GlobalProps;

const perPage = 12;

const DynamicAdminOverlay = dynamic(() => import("~/components/AdminOverlay"), {
  ssr: false,
});

const IndexAlbum: NextPage<Props> = ({
  categories,
  pagination,
  socialMedias,
  appName,
}: Props) => {
  const { isAdmin } = useAuthentication();

  const title = `Catégories page ${pagination.currentPage} - Photographe | ${appName}`;
  const description =
    "Besoin de s’évader dans un domaine précis ? Venez naviguer à travers les albums grâce aux catégories.";

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={title}
        description={description}
        openGraph={{
          title: title,
          description,
          images: categories
            .filter((c) => c.cover !== null)
            .map((c) => ({
              url: c.cover?.thumb ?? "",
              width: c.cover?.width,
              height: c.cover?.height,
              alt: c.name,
            })),
        }}
      />
      <Header title={"Par thématique"} description={description} />
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
    fallback: false,
  };
};
