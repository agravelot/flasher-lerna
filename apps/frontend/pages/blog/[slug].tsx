import { GetStaticPaths, GetStaticProps, NextPage } from "next";
import React from "react";
import { ArticleJsonLd, NextSeo } from "next-seo";
import { serialize } from "next-mdx-remote/serialize";
import { BlogPost, getAllPosts, getPostBySlug } from "utils/markdown";
import { getGlobalProps, GlobalProps } from "stores";
import Layout from "components/Layout";
import { configuration } from "utils/configuration";
import Header from "components/Header";
import { MDXRemote } from "next-mdx-remote";
import { generateNextImageUrl } from "utils/util";
import { useRouter } from "next/router";
import { ContactSection } from "components/ContactSection";
import ReadingTime from "components/ReadingTime";
import { Breadcrumb } from "components/Breadcrumb";
import { removeQueryParams } from "../../utils/canonical";

type Props = {
  post: BlogPost;
} & GlobalProps;

const ImageCustom = (
  props: React.DetailedHTMLProps<
    React.ImgHTMLAttributes<HTMLImageElement>,
    HTMLImageElement
  >
) => {
  return (
    // eslint-disable-next-line @next/next/no-img-element
    <img
      // eslint-disable-next-line react/prop-types
      src={generateNextImageUrl(props.src as string)}
      loading="lazy"
      // eslint-disable-next-line react/prop-types
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

const Post: NextPage<Props> = ({ post, appName, socialMedias }: Props) => {
  const { asPath } = useRouter();

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={post.title}
        description={post.metaDescription}
        canonical={`${configuration.appUrl}${removeQueryParams(asPath)}`}
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
        type="Blog"
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
      <Header
        title={post.title}
        breadcrumb={
          <Breadcrumb
            levels={[
              { name: "Acceuil", path: "/" },
              { name: "Blog", path: "/blog" },
              { name: post.title, path: `/blog/${post.slug}` },
            ]}
          />
        }
      />
      <div className="container prose-sm prose mx-auto max-w-none py-16 px-4 sm:prose lg:prose-lg xl:prose-xl">
        {post.content && post.content.length !== 0 && (
          <ReadingTime body={post.content} />
        )}
        <div className="flex justify-center text-justify">
          <article className="max-w-none content-center">
            {post.contentSerialized && (
              <MDXRemote {...post.contentSerialized} components={components} />
            )}
          </article>
        </div>
      </div>

      <ContactSection />
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
    return { notFound: true, revalidate: 60 };
  }

  const global = await getGlobalProps();

  post.contentSerialized = await serialize(post.content);

  return {
    props: {
      ...global,
      post,
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
