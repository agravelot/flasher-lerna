import { GetStaticPaths, GetStaticProps, NextPage } from "next";
import React from "react";
import { ArticleJsonLd, NextSeo } from "next-seo";
import { serialize } from "next-mdx-remote/serialize";
import { ImageProps } from "next/image";
import { BlogPost, getAllPosts, getPostBySlug } from "utils/markdown";
import { getGlobalProps, GlobalProps } from "stores";
import Layout from "components/Layout";
import { configuration } from "utils/configuration";
import Header from "components/Header";
import { MDXRemote } from "next-mdx-remote";
import { generateNextImageUrl } from "utils/util";
import dynamic from "next/dynamic";
import useInView from "react-cool-inview";
import { useRouter } from "next/dist/client/router";
import ReadingTime from "components/ReadingTime";

const Comments = dynamic(() => import("../../components/Comments"), { ssr: false });

type Props = {
  post: BlogPost;
  estimatedReadingInMinutes: number;
} & GlobalProps;

const ImageCustom = (props: ImageProps) => {
  // return (
  //   <Image layout="responsive" width="2000" height="2000" src={props.src} />
  // );

  return (
    // eslint-disable-next-line @next/next/no-img-element
    <img
      src={generateNextImageUrl(props.src as string)}
      loading="lazy"
      alt={props.alt}
    />
  );
};

const components = {
  img: ImageCustom,
  // h1: (props) => <h1 style={{ color: "tomato" }} {...props} />,
  // h2: (props) => <h2 style={{ color: "tomato" }} {...props} />,
  // p: (props) => <p style={{ color: "tomato" }} {...props} />,
};

const Post: NextPage<Props> = ({ post, appName, estimatedReadingInMinutes, socialMedias }: Props) => {
  const { observe, inView } = useInView({
    onEnter: ({ unobserve }) => unobserve(), // only run once
  });
  const { asPath } = useRouter();

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={post.title}
        description={post.metaDescription}
        canonical={`${configuration.appUrl}${asPath}`}
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
      <div className="container mx-auto py-16 px-4 max-w-none prose prose-sm sm:prose lg:prose-lg xl:prose-xl">
          <ReadingTime body={post.content}/>
        <div className="flex justify-center text-justify">

          <article className="content-center max-w-none">
            {post.contentSerialized && (
              <MDXRemote {...post.contentSerialized} components={components} />
            )}
          </article>
        </div>
      </div>

      <div ref={observe} className="mt-8 mb-32 container mx-auto">
        { inView && <Comments url={`${configuration.appUrl}/blog/${post.slug}`} identifier={`/blog/${post.slug}`} title={post.title} /> }
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
  const post = preview
    ? (previewData as BlogPost)
    : await getPostBySlug(params?.slug as string);

  if (!post) {
    return { notFound: true };
  }

  const global = await getGlobalProps();
  
  post.contentSerialized = await serialize(post.content);

  const estimatedReadingInMinutes = (post.content.split(" ").length / 200).toFixed();

  return {
    props: {
      ...global,
      post,
      estimatedReadingInMinutes
    },
  };
};

export const getStaticPaths: GetStaticPaths = async () => {
  const posts = await getAllPosts();

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
