import {
  GetStaticPaths,
  GetStaticProps,
  GetStaticPropsContext,
  NextPage,
} from "next";
import Layout from "../../components/Layout";
import Header from "../../components/Header";
import AlbumItem from "../../components/album/AlbumItem";
import { getGlobalProps, GlobalProps } from "../../stores";
import dynamic from "next/dynamic";
import { NextSeo } from "next-seo";
import { configuration } from "../../utils/configuration";
import {
  api,
  HttpNotFound,
  PaginatedReponse,
  WrappedResponse,
} from "@flasher/common";
import { Album, Category } from "@flasher/models";
import { useAuthentication } from "hooks/useAuthentication";
import { useRouter } from "next/dist/client/router";
import { Breadcrumb } from "components/Breadcrumb";
import { removeQueryParams } from "../../utils/canonical";
import { useEffect, useState } from "react";
import { useInView } from "react-cool-inview";

type Props = {
  category: Category;
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

const ShowCategory: NextPage<Props> = ({
  category,
  albums: initialAlbums,
  hasNextPage: initialHasNextPage,
  socialMedias,
  appName,
}: Props) => {

  const [currentPage, setCurrentPage] = useState(1);
  const [hasNextPage, setHasNextPage] = useState(initialHasNextPage);
  const [albums, setAlbums] = useState(initialAlbums);
  const { isAdmin } = useAuthentication();
  const { asPath } = useRouter();

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
    const url = getApiUrl(category.id, currentPage, perPage);
    api<PaginatedReponse<Album[]>>(url)
      .then((res) => res.json())
      .then((res) => {
        setAlbums((previous) => [...previous, ...res.data]);
        setCurrentPage(currentPage);
        setHasNextPage(currentPage < res.meta.last_page);
      });
  }, [category, currentPage]);

  useEffect(() => {
    if (!hasNextPage) {
      unobserve();
    }
  }, [hasNextPage, unobserve]);

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`${category.name} - Photographe | ${appName}`}
        canonical={`${configuration.appUrl}${removeQueryParams(asPath)}`}
        description={category.meta_description}
        openGraph={{
          title: `${category.name} - Photographe | ${appName}`,
          type: "article",
          article: {
            publishedTime: String(category.created_at),
            modifiedTime: category.updated_at
              ? String(category.updated_at)
              : String(category.created_at),
            authors: [configuration.appUrl],
            section: "Photography",
          },
          images: [
            {
              url: category.cover?.url ?? "",
              width: category.cover?.width,
              height: category.cover?.height,
              alt: category.name,
            },
          ],
        }}
      />
      <div>
        <Header
          src={category.cover?.url ?? undefined}
          title={category.name}
          description={`Découvrez les albums de la catégorie ${category.name.toLowerCase()}`}
          breadcrumb={
            <Breadcrumb
              levels={[
                { name: "Acceuil", path: "/" },
                { name: "Catégorie ", path: "/categories" },
                { name: category.name, path: `/categories/${category.slug}` },
              ]}
            />
          }
        />

        <div className="container mx-auto px-4">
          <div className="justify-center py-16 text-justify">
            <article
              className="prose prose-stone max-w-none content-center"
              dangerouslySetInnerHTML={{ __html: category.description ?? "" }}
            />
          </div>
        </div>
        <div className="container mx-auto mb-8 py-8">
          <div className="-mx-2 flex flex-wrap">
            {albums.map((album) => (
              <div
                key={album.id}
                className="w-full flex-grow-0 p-1 md:w-1/2 lg:w-1/3"
              >
                <AlbumItem album={album} />
              </div>
            ))}
          </div>
        </div>
        {hasNextPage && <span ref={observe} />}
      </div>
      {isAdmin && <DynamicAdminOverlay />}
    </Layout>
  );
};

export default ShowCategory;

const getApiUrl = (categoryId: number, page: number, perPage: number) =>
  `/albums?filter[categories.id]=${categoryId}&page=${page}&per_page=${perPage}`;

export const getStaticProps: GetStaticProps<Props> = async ({
  params,
}: GetStaticPropsContext) => {
  try {
    const category = await api<WrappedResponse<Category>>(
      `/categories/${params?.slug}`
    )
      .then((res) => res.json())
      .then((res) => res.data);


    const res = await api<PaginatedReponse<Album[]>>(getApiUrl(category.id, 1, perPage))
      .then((res) => res.json());

    const global = await getGlobalProps();

    return { props: { category, albums: res.data, hasNextPage: res.meta.current_page < res.meta.last_page, ...global }, revalidate: 60 };
  } catch (e) {
    if (e instanceof HttpNotFound) {
      return { notFound: true, revalidate: 60 };
    }
    throw e;
  }
};

export const getStaticPaths: GetStaticPaths = async () => {
  const categories = await api<PaginatedReponse<Category[]>>("/categories")
    .then((res) => res.json())
    .then((res) => res.data);
  const paths = categories.map((category) => ({
    params: { slug: category.slug },
  }));

  return { paths, fallback: "blocking" };
};
