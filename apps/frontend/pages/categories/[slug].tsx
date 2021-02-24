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
  PaginatedReponse,
  useAuthentication,
  WrappedResponse,
} from "@flasher/common";
import { Album, Category } from "@flasher/models";

type Props = {
  category: Category;
  albums: Album[];
} & GlobalProps;

const DynamicAdminOverlay = dynamic(
  () => import("../../components/AdminOverlay"),
  {
    ssr: false,
  }
);

const ShowCategory: NextPage<Props> = ({
  category,
  albums,
  socialMedias,
  appName,
}: Props) => {
  const { isAdmin } = useAuthentication();

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`${category.name} - Photographe | ${appName}`}
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
        {category.cover && (
          <Header
            src={category.cover?.url}
            title={category.name}
            description={`Découvrez les albums de la catégorie ${category.name.toLowerCase()}`}
          />
        )}

        {!category.cover && (
          <Header
            title={category.name}
            description={`Découvrez les albums de la catégorie ${category.name.toLowerCase()}`}
          />
        )}

        <div className="container mx-auto px-4">
          <div className="justify-center py-16 text-justify">
            <article
              className="content-center prose max-w-none"
              dangerouslySetInnerHTML={{ __html: category.description ?? "" }}
            />
          </div>
        </div>

        <div className="container mx-auto py-8 mb-8">
          <div className="flex flex-wrap -mx-2">
            {albums.map((album) => (
              <div
                key={album.id}
                className="w-full p-1 flex-grow-0 md:w-1/2 lg:w-1/3"
              >
                <AlbumItem album={album} />
              </div>
            ))}
          </div>
        </div>
      </div>
      {isAdmin && <DynamicAdminOverlay />}
    </Layout>
  );
};

export default ShowCategory;

export const getStaticProps: GetStaticProps<Props> = async ({
  params,
}: GetStaticPropsContext) => {
  const category = await api<WrappedResponse<Category>>(
    `/categories/${params?.slug}`
  )
    .then((res) => res.json())
    .then((res) => res.data);
  const albums = await api<PaginatedReponse<Album[]>>(
    `/albums?filter[categories.id]=${category.id}`
  )
    .then((res) => res.json())
    .then((res) => res.data);

  const global = await getGlobalProps();

  return { props: { category, albums, ...global }, revalidate: 60 };
};

export const getStaticPaths: GetStaticPaths = async () => {
  const categories = await api<PaginatedReponse<Category[]>>("/categories")
    .then((res) => res.json())
    .then((res) => res.data);
  const paths = categories.map((category) => ({
    params: { slug: category.slug },
  }));

  return { paths, fallback: false };
};
