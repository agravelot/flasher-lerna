import { GetStaticPaths, GetStaticProps, NextPage } from "next";
import React from "react";
import { ArticleJsonLd, NextSeo } from "next-seo";
import renderToString from "next-mdx-remote/render-to-string";
import hydrate from "next-mdx-remote/hydrate";
import { ImageProps } from "next/image";
import { BlogPost, getAllPosts, getPostBySlug } from "utils/markdown";
import { getGlobalProps, GlobalProps } from "stores";
import Layout from "components/Layout";
import { configuration } from "utils/configuration";
import Header from "components/Header";
import { MdxRemote } from "next-mdx-remote/types";

type Props = {
  post: BlogPost;
} & GlobalProps;

const ImageCustom = (props: ImageProps) => {
  // return (
  //   <Image layout="responsive" width="2000" height="2000" src={props.src} />
  // );

  return (
    <img
      src={"/_next/image?url=" + encodeURIComponent(props.src) + "&w=828&q=75"}
      loading="lazy"
    />
  );
};

const components = {
  img: ImageCustom,
  // h1: (props) => <h1 style={{ color: "tomato" }} {...props} />,
  // h2: (props) => <h2 style={{ color: "tomato" }} {...props} />,
  // p: (props) => <p style={{ color: "tomato" }} {...props} />,
};

const Post: NextPage<Props> = ({ post, appName, socialMedias }: Props) => {
  const content = hydrate(post.content as MdxRemote.Source, { components });
  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={post.title}
        description={post.metaDescription}
        additionalMetaTags={[{ name: "author", content: appName }]}
        openGraph={{
          title: post.title,
          description: post.metaDescription,
          type: "article",
          //   images: album.medias?.slice(0, 5).flatMap((m) => ({
          //     url: m.thumb,
          //     alt: m.name,
          //     width: m.width,
          //     height: m.height,
          //   })),
          article: {
            publishedTime: post.createdAt ?? "",
            modifiedTime: post.updatedAt ?? post.createdAt ?? "",
            authors: [appName],
            section: "Photography",
            // tags: album.categories?.map((category) => category.name),
          },
        }}
      />
      <ArticleJsonLd
        // url={url}
        url={`${configuration.appUrl}/blog/${post.slug}`}
        title={post.title}
        images={[]}
        // images={album.medias?.slice(0, 5).flatMap((m) => m.thumb) ?? []}
        datePublished={post.createdAt}
        dateModified={post.updatedAt ?? post.createdAt}
        authorName={appName}
        publisherLogo={"/icon-512x512.png"} // TODO
        publisherName={appName}
        description={post.metaDescription}
      />
      <Header title={post.title} />
      <div className="container mx-auto">
        <div className="flex justify-center py-16 px-4 text-justify">
          <article className="content-center max-w-none prose prose-sm sm:prose lg:prose-lg xl:prose-xl">
            {content}
          </article>
        </div>
      </div>
    </Layout>
  );
};

export default Post;

export const getStaticProps: GetStaticProps = async ({
  params,
  preview,
  previewData,
}) => {
  const post = preview ? previewData : getPostBySlug(params?.slug as string);

  const global = await getGlobalProps();

  post.content = await renderToString(post.content, { components });

  return {
    props: {
      ...global,
      post,
    },
  };
};

export const getStaticPaths: GetStaticPaths = async () => {
  const posts = getAllPosts();

  return {
    paths: posts.map((post) => {
      return {
        params: {
          slug: post.slug,
        },
      };
    }),
    fallback: false,
  };
};
