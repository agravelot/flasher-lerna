import { GetStaticPaths, GetStaticProps, NextPage } from "next";
import Layout from "../../components/Layout";
import Avatar from "../../components/Avatar";
import { range } from "../../utils/util";
import AlbumItem from "../../components/album/AlbumItem";
import { getGlobalProps, GlobalProps } from "../../stores";
import Separator from "../../components/Separator";
import dynamic from "next/dynamic";
import { NextSeo } from "next-seo";
import { Album, Cosplayer } from "@flasher/models";
import {
  api,
  HttpNotFound,
  PaginatedReponse,
  useAuthentication,
  WrappedResponse,
} from "@flasher/common";

type Props = {
  cosplayer: Cosplayer;
  albums: Album[];
} & GlobalProps;

const DynamicAdminOverlay = dynamic(
  () => import("../../components/AdminOverlay"),
  {
    ssr: false,
  }
);

const ShowCosplayer: NextPage<Props> = ({
  cosplayer,
  albums,
  socialMedias,
  appName,
}: Props) => {
  const { isAdmin } = useAuthentication();

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`${cosplayer.name} - Cosplayeur - Photographe | ${appName}`}
        description={cosplayer.description ?? ""}
        openGraph={{
          title: `${cosplayer.name} - Cosplayeur - Photographe | ${appName}`,
          description: cosplayer.description ?? "",
          type: "profile",
          profile: {
            username: cosplayer.name,
          },
          images: [cosplayer.avatar, ...albums.map((a) => a.media)].map(
            (cover) => ({
              url: cover?.url ?? "",
              height: cover?.height,
              width: cover?.width,
            })
          ),
        }}
      />
      <div>
        <section className="relative block" style={{ height: "500px" }}>
          <div
            className="absolute top-0 w-full h-full bg-center bg-cover"
            style={{
              backgroundImage:
                "url('https://images.unsplash.com/photo-1499336315816-097655dcfbda?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=2710&amp;q=80')",
            }}
          >
            <span
              id="blackOverlay"
              className="w-full h-full absolute opacity-50 bg-black"
            ></span>
          </div>

          <Separator separatorClass="text-white" position="bottom" />
        </section>
        <section className="relative py-16">
          <div className="container mx-auto px-4">
            <div className="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-xl rounded-lg -mt-64">
              <div className="px-6">
                <div className="flex flex-wrap justify-center">
                  <div className="w-full lg:w-3/12 px-4 lg:order-2 flex justify-center">
                    <div className="relative">
                      <div className="-mt-16">
                        <Avatar
                          name={cosplayer.name}
                          src={cosplayer.avatar?.url}
                          size={24}
                          border={true}
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div className="text-center mt-12">
                  <h1 className="text-4xl font-semibold leading-normal mb-2 text-gray-800">
                    {cosplayer.name}
                  </h1>
                  <div className="text-sm leading-normal mt-0 mb-2 text-gray-500 font-bold uppercase">
                    <i className="fas fa-map-marker-alt mr-2 text-lg text-gray-500"></i>
                    Cosplayer
                  </div>
                </div>
                <div className="mt-10 py-10 border-t border-gray-300 text-center">
                  <div className="flex flex-wrap justify-center">
                    <div className="w-full lg:w-9/12 px-4">
                      <div className="mb-4 text-lg leading-relaxed text-gray-800">
                        <div
                          className="prose max-w-none"
                          dangerouslySetInnerHTML={{
                            __html: cosplayer.description ?? "",
                          }}
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section className="pb-24">
          <div className="container mx-auto">
            <div className="flex justify-center">
              <h2 className="text-2xl font-bold pb-12">
                Retrouvez les albums de {cosplayer.name}
              </h2>
            </div>
            <div className="flex flex-wrap md:-mx-3">
              {albums.map((album) => (
                <div className="w-full md:w-1/3 p-3" key={album.id}>
                  <AlbumItem album={album} />
                </div>
              ))}
            </div>
          </div>
        </section>
      </div>
      {isAdmin && <DynamicAdminOverlay />}
    </Layout>
  );
};

export default ShowCosplayer;

export const getStaticProps: GetStaticProps = async ({ params }) => {
  try {
    const cosplayer = await api<WrappedResponse<Cosplayer>>(
      `/cosplayers/${params?.slug}`
    )
      .then((res) => res.json())
      .then((json) => json.data)
      .catch((e) => {
        throw e;
      });

    const albums = await api<PaginatedReponse<Album[]>>(
      `/albums?filter[cosplayers.id]=${cosplayer.id}`
    )
      .then((res) => res.json())
      .then((json) => json.data)
      .catch((e) => {
        throw e;
      });

    const global = await getGlobalProps();

    return { props: { cosplayer, albums, ...global }, revalidate: 60 };
  } catch (e) {
    if (e instanceof HttpNotFound) {
      return { notFound: true };
    }
    throw e;
  }
};

export const getStaticPaths: GetStaticPaths = async () => {
  const res = await api<PaginatedReponse<Album[]>>("/cosplayers").then((res) =>
    res.json()
  );

  const albums: Album[][] = await Promise.all(
    range(1, res.meta.last_page).map(async (page) => {
      return await api<PaginatedReponse<Album[]>>(`/cosplayers?page=${page}`)
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
