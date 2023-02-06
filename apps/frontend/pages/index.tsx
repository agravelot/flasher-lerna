import Link from "next/link";
import Layout from "../components/Layout";
import Image from "next/image";
import Header from "../components/Header";
import { TestimonialList } from "../components/TestimonialList";
import AlbumList from "../components/album/AlbumList";
import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import { getGlobalProps, GlobalProps } from "../stores";
import Separator from "../components/Separator";
import { NextSeo, SiteLinksSearchBoxJsonLd } from "next-seo";
import { Album, Testimonial } from "@flasher/models";
import { api, PaginatedReponse, sizes } from "@flasher/common";
import { configuration } from "utils/configuration";
import { ContactSection } from "../components/ContactSection";
import SearchOpener from "components/SearchOpener";

type Props = {
  albums: Album[];
  testimonials: Testimonial[];
} & GlobalProps;

const IndexPage: NextPage<Props> = ({
  albums,
  testimonials,
  defaultPageTitle,
  appName,
  profilePictureHomepage,
  homepageDescription,
  socialMedias,
  seoDescription,
}: Props) => {
  if (!profilePictureHomepage) {
    console.error("Missing profile picture");
  }
  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <SearchOpener/>
      <NextSeo
        title={defaultPageTitle}
        description={seoDescription}
        canonical={`${configuration.appUrl}`}
        openGraph={
          profilePictureHomepage
            ? {
                images: [
                  {
                    url: profilePictureHomepage.url,
                    width: profilePictureHomepage.width ?? 0,
                    height: profilePictureHomepage.height ?? 0,
                    alt: appName,
                  },
                ],
              }
            : undefined
        }
      />
      <SiteLinksSearchBoxJsonLd
        url="https://jkanda.fr"
        potentialActions={[
          {
            target: "https://jkanda.fr?search=search_term",
            queryInput: "search_term",
          },
        ]}
      />
      <div>
        <Header title={appName} separatorClass="text-gray-300" />
        <section className="bg-gray-300 pb-20">
          <div className="container mx-auto">
            <div className="-mt-24 flex flex-wrap overflow-x-hidden md:-mx-3">
              <Link
                href="/albums/page/1"
                tabIndex={0}
                className="order-last mx-auto mt-12 rounded-lg bg-gradient-to-r from-blue-700 to-red-700 py-3 px-10 font-semibold text-white hover:from-pink-500 hover:to-orange-500"
              >
                <h2>Découvrez mes derniers albums</h2>
              </Link>
              <AlbumList albums={albums} className="-mt-24" />
            </div>
          </div>
        </section>
        <section className="relative py-20">
          <Separator separatorClass="text-white" position="top" />
          <div className="container mx-auto px-4">
            <div className="block flex-wrap items-center lg:flex">
              {profilePictureHomepage && (
                <div className="mx-auto w-full lg:order-2 lg:w-4/12">
                  <Image
                    className="mb-8 w-full object-cover p-8 shadow-lg lg:mb-0"
                    alt={appName}
                    src={profilePictureHomepage.url}
                    width={2000}
                    draggable={false}
                    height={2000}
                    sizes={sizes(3, "container")}
                  />
                </div>
              )}
              <div className="ml-auto mr-auto w-full px-4 lg:w-8/12">
                <div className="md:pr-12">
                  <h2 className="text-3xl font-semibold">
                    Photographe passionnée sur Lyon
                  </h2>
                  <div className="mt-2">
                    <span className="mx-auto mb-4 inline-block h-1 w-16 rounded bg-gradient-to-r from-blue-700 to-red-700"></span>
                  </div>
                  <div
                    className="prose mt-4 max-w-none text-justify leading-relaxed"
                    dangerouslySetInnerHTML={{
                      __html: homepageDescription,
                    }}
                  />
                </div>
              </div>
            </div>
          </div>
        </section>
        <section id="testimonials" className="p-12 lg:pb-24">
          <div className="container mx-auto">
            <div className="flex flex-wrap justify-center text-center">
              <div className="w-full px-4 lg:w-6/12">
                <h2 className="text-4xl font-semibold">
                  <span>
                    {"Ils sont "}
                    <span
                      className="bg-gradient-to-r from-blue-700 to-red-700 bg-clip-text text-transparent shadow-none"
                      style={{
                        WebkitBackgroundClip: "text",
                      }}
                    >
                      ravis
                    </span>
                  </span>
                </h2>
                <span className="mb-4 inline-block h-1 w-10 rounded bg-gradient-to-r from-blue-700 to-red-700" />
                <p className="m-4 text-lg leading-relaxed">
                  Vous avez aimer partager cette aventure avec moi ? N’hésitez
                  pas à en laisser une trace, cela fait toujours plaisir.
                </p>
              </div>
            </div>
            <div className="body-font">
              <TestimonialList testimonials={testimonials} appName={appName} />
            </div>
            {/* <div className="flex items-center">
              <button
                className="my-8 bg-gradient-to-r mx-auto from-blue-700 to-red-700 hover:from-pink-500 hover:to-orange-500 text-white font-semibold py-3 px-10 rounded-lg"
                onClick={() => toggleTestimonialModal()}
                tabIndex={0}
              >
                Ajouter mon message
              </button>
            </div> */}
          </div>
        </section>
        <ContactSection />
      </div>
    </Layout>
  );
};

export default IndexPage;

export const getStaticProps: GetStaticProps = async ({
  params,
}): Promise<GetStaticPropsResult<Props>> => {
  const albums = await api<PaginatedReponse<Album[]>>(
    `/albums?page=${params?.page ?? 1}`
  )
    .then((res) => res.json())
    .then((res) => res.data.slice(0, 3));
  const testimonials = await api<PaginatedReponse<Testimonial[]>>(
    `/testimonials?page=${params?.page ?? 1}`
  )
    .then((res) => res.json())
    .then((res) => res.data.reverse());

  const global = await getGlobalProps();

  return { props: { albums, testimonials, ...global }, revalidate: 60 };
};
