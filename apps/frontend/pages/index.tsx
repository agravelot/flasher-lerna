import Link from "next/link";
import Layout from "../components/Layout";
import Image from "next/image";
import Header from "../components/Header";
import { TestimonialList } from "../components/TestimonialList";
import AlbumList from "../components/album/AlbumList";
import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import { getGlobalProps, GlobalProps } from "../stores";
import Separator from "../components/Separator";
import { NextSeo } from "next-seo";
import { Album, Testimonial } from "@flasher/models";
import { api, PaginatedReponse } from "@flasher/common";
import { configuration } from "utils/configuration";
import { ContactSection } from "../components/ContactSection";

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
      <NextSeo
        title={defaultPageTitle}
        description={seoDescription}
        canonical={`${configuration.appUrl}`}
        openGraph={profilePictureHomepage ? {
          images: [
            {
              url: profilePictureHomepage.url,
              width: profilePictureHomepage.width ?? 0,
              height: profilePictureHomepage.height ?? 0,
              alt: appName,
            },
          ],
        }: undefined}
      />
      <div>
        <Header title={appName} separatorClass="text-gray-300" />
        <section className="pb-20 bg-gray-300">
          <div className="container mx-auto">
            <div className="flex flex-wrap -mt-24 md:-mx-3 overflow-x-hidden">
              <Link href="/albums/page/1">
                <a
                  tabIndex={0}
                  className="order-last mt-12 mx-auto bg-gradient-to-r from-blue-700 to-red-700 hover:from-pink-500 hover:to-orange-500 text-white font-semibold py-3 px-10 rounded-lg"
                >
                  <h2>Découvrez mes derniers albums</h2>
                </a>
              </Link>
              <AlbumList albums={albums} className="-mt-24" />
            </div>
          </div>
        </section>
        <section className="relative py-20">
          <Separator separatorClass="text-white" position="top" />
          <div className="container mx-auto px-4">
            <div className="items-center block lg:flex flex-wrap">
              {profilePictureHomepage && <div className="mx-auto w-3/5 lg:w-4/12 lg:order-2">
                <Image
                  className="shadow-lg object-cover w-full p-8 md:p-16 mb-8 lg:mb-0"
                  alt={appName}
                  src={profilePictureHomepage.url}
                  width={2000}
                  draggable={false}
                  height={2000}
                  // quality={95}
                />
              </div>}
              <div className="w-full lg:w-8/12 ml-auto mr-auto px-4">
                <div className="md:pr-12">
                  <h2 className="text-3xl font-semibold">
                    Photographe passionnée sur Lyon
                  </h2>
                  <div className="mt-2">
                    <span className="mx-auto inline-block h-1 w-16 rounded bg-gradient-to-r from-blue-700 to-red-700 mb-4"></span>
                  </div>
                  <div
                    className="mt-4 leading-relaxed prose max-w-none text-justify"
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
              <div className="w-full lg:w-6/12 px-4">
                <h2 className="text-4xl font-semibold">
                  <span>
                    {"Ils sont "}
                    <span
                      className="bg-clip-text bg-gradient-to-r from-blue-700 to-red-700 shadow-none text-transparent"
                      style={{
                        WebkitBackgroundClip: "text",
                      }}
                    >
                      ravis
                    </span>
                  </span>
                </h2>
                <span className="inline-block h-1 w-10 rounded bg-gradient-to-r from-blue-700 to-red-700 mb-4" />
                <p className="text-lg leading-relaxed m-4">
                  Vous avez aimer partager cette aventure avec moi ? N’hésitez
                  pas à en laisser une trace, cela fait toujours plaisir.
                </p>
              </div>
            </div>
            <div className="body-font">
                  <TestimonialList
                    testimonials={testimonials}
                    appName={appName}
                  />
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
