import Link from "next/link";
import Layout from "../components/Layout";
import Image from "next/image";
import Header from "../components/Header";
import ContactForm from "../components/ContactForm";
import { TestimonialList } from "../components/TestimonialList";
import AlbumList from "../components/album/AlbumList";
import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import { getGlobalProps, GlobalProps } from "../stores";
import Separator from "../components/Separator";
import { NextSeo } from "next-seo";
import { Album, Testimonial } from "@flasher/models";
import { api, PaginatedReponse } from "@flasher/common";

const toggleTestimonialModal = () => {
  return;
};

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
  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={defaultPageTitle}
        description={seoDescription}
        openGraph={{
          images: [
            {
              url: profilePictureHomepage.url,
              width: profilePictureHomepage.width ?? 0,
              height: profilePictureHomepage.height ?? 0,
              alt: appName,
            },
          ],
        }}
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
              <div className="mx-auto w-3/5 lg:w-4/12 lg:order-2">
                <Image
                  className="shadow-lg object-cover w-full p-8 md:p-16 mb-8 lg:mb-0"
                  alt={appName}
                  src={profilePictureHomepage.url}
                  width={2000}
                  draggable={false}
                  height={2000}
                  // quality={95}
                />
              </div>
              <div className="w-full lg:w-8²/12 ml-auto mr-auto px-4">
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
            <div className="text-gray-700 body-font">
              <div className="pt-12">
                <div className="flex flex-nowrap overflow-x-auto w-full">
                  <TestimonialList
                    testimonials={testimonials}
                    appName={appName}
                  />
                </div>
              </div>
            </div>
            <div className="flex items-center">
              <button
                className="my-8 bg-gradient-to-r mx-auto from-blue-700 to-red-700 hover:from-pink-500 hover:to-orange-500 text-white font-semibold py-3 px-10 rounded-lg"
                onClick={() => toggleTestimonialModal()}
                tabIndex={0}
              >
                Ajouter mon message
              </button>
            </div>
          </div>
        </section>
        <section className="pb-20 relative bg-gray-900">
          <Separator separatorClass="text-gray-900" position="top" />
          <div id="contact" className="container mx-auto px-4 py-12 lg:pt-24">
            <div className="flex flex-wrap text-center justify-center">
              <div className="w-full lg:w-6/12 px-4">
                <h2 className="text-4xl font-semibold text-white">
                  Envie de réaliser un projet ?
                </h2>
                <p className="text-lg leading-relaxed mt-4 mb-4 text-gray-200">
                  Réservez votre séance photo grâce à ce formulaire de contact !
                  N’hésitez pas non plus à poser vos questions si vous en avez
                  grâce à celui-ci. Je vous répondrai dans les 24 heures.
                </p>
                <div className="text-white flex">
                  <div className="flex mx-auto">
                    <svg
                      className="h-6 w-6 mr-4 my-auto"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                      />
                    </svg>
                    <div>
                      <a
                        className="text-xl font-semibold"
                        href="tel:+33766648588"
                      >
                        07 66 64 85 88
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="container mx-auto px-4">
            <div className="flex flex-wrap justify-center">
              <div className="w-full lg:w-6/12 px-4">
                <div className="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-gray-300">
                  <div className="flex-auto p-5 lg:p-10">
                    <div className="flex justify-center">
                      <h3 className="text-xl font-medium my-4">
                        Formulaire de contact
                      </h3>
                    </div>
                    <ContactForm />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
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
    .then((res) => res.data);

  const global = await getGlobalProps();

  return { props: { albums, testimonials, ...global }, revalidate: 60 };
};
