import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import { NextSeo } from "next-seo";
import { useRouter } from "next/dist/client/router";
import React from "react";
import Link from "next/link";
import { formatDistance } from "date-fns";
import { fr } from "date-fns/locale";
import { BlogPost, getAllPosts } from "utils/markdown";
import { getGlobalProps, GlobalProps } from "stores";
import Layout from "components/Layout";
import { configuration } from "utils/configuration";
import Header from "components/Header";

type Props = {
  posts: BlogPost[];
} & GlobalProps;

const IndexAlbum: NextPage<Props> = ({
  posts,
  socialMedias,
  appName,
}: Props) => {
  const { asPath } = useRouter();

  const title = `Blog - Photographe | ${appName}`;
  const description =
    "Venez plonger dans mes différents univers à travers mes articles divers et variés ! Entre balade, cosplay, et portrait.";

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={title}
        canonical={`${configuration.appUrl}${asPath}`}
        description={description}
        openGraph={{
          title: title,
          description: description,
          url: configuration.appUrl + asPath,
          /*  images: albums
            .map((a) => ({
              url: a.media?.thumb ?? "",
              width: a.media?.width,
              height: a.media?.height,
              alt: a.title,
            }))
            .slice(0, 5), */
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
      <div className="container mx-auto py-8">
        {posts.map((post) => {
          const createdAt = new Date(post.createdAt);

          return (
            <div className="w-full p-3" key={post.slug}>
              <div className="max-w-2xl mx-auto mb-10">
                <span className="text-gray-600 text-sm font-light">
                  Il y a{" "}
                  {formatDistance(createdAt, new Date(), {
                    locale: fr,
                    addSuffix: false,
                  })}
                </span>
                <Link href={`/blog/${post.slug}`}>
                  <a className="block">
                    <h3 className=" text-gray-800 text-3xl font-bold mt-2 hover:underline hover:text-blue-500">
                      {post.title}
                    </h3>
                  </a>
                </Link>
                <p className="text-gray-600 mt-4">{post.metaDescription}</p>
                <Link href={`/blog/${post.slug}`}>
                  <a className="inline-block w-full">
                    <span
                      className="bg-gradient-to-r from-blue-700 to-red-700 mx-4 shadow-none bg-clip-text text-transparent float-right font-semibold hover:underline"
                      style={{
                        WebkitBackgroundClip: "text",
                      }}
                    >
                      Lire la suite
                    </span>
                  </a>
                </Link>

                <span className="inline-block h-px w-full rounded bg-gradient-to-r from-blue-700 to-red-700 opacity-50"></span>
                {/* <div className="mt-4">
                    <div className="inline-flex items-center">
                      <img
                        src="https://www.gravatar.com/avatar/e2b7df7dc785083174cf4d4242c03285"
                        alt=""
                        className="h-8 w-8 rounded-full"
                      />{" "}
                      <h3 className="text-gray-800 font-medium ml-3 capitalize">
                        {post.author}
                      </h3>
                    </div>
                  </div> */}
              </div>
            </div>
          );
        })}
      </div>
    </Layout>
  );
};

export default IndexAlbum;

export const getStaticProps: GetStaticProps = async (): Promise<
  GetStaticPropsResult<Props>
> => {
  const posts = await getAllPosts("published");
  const global = await getGlobalProps();

  return {
    props: {
      ...global,
      posts,
    },
  };
};
