import Layout from "../components/Layout";
import Header from "../components/Header";
import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import { getGlobalProps, GlobalProps } from "../stores";
import { MDXRemote, MDXRemoteSerializeResult } from "next-mdx-remote";
import { generateNextImageUrl } from "@flasher/common/src";
import { serialize } from "next-mdx-remote/serialize";
import { join } from "path";
import { readFileSync } from "fs";
import Link from "next/link";

type Props = GlobalProps & {
  content: MDXRemoteSerializeResult;
};

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
  a: (
    a: React.DetailedHTMLProps<
      React.AnchorHTMLAttributes<HTMLAnchorElement>,
      HTMLAnchorElement
    >
  ) => {
    if (!a.href) {
      throw new Error("no href");
    }
    const isInternal = a.href.startsWith(process.env.NEXT_PUBLIC_APP_URL ?? "");

    if (!isInternal) {
      return (
        <a href={a.href} target="_blank" rel="noreferrer">
          {a.children}
        </a>
      );
    }

    return <Link href={a.href}>{a.children}</Link>;
  },
  // h1: (props) => <h1 style={{ color: "tomato" }} {...props} />,
  // h2: (props) => <h2 style={{ color: "tomato" }} {...props} />,
  // p: (props) => <p style={{ color: "tomato" }} {...props} />,
};

const IndexPage: NextPage<Props> = ({
  appName,
  socialMedias,
  content,
}: Props) => {
  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <Header title="Mentions légales" separatorClass="text-white" />

      <div className="container mx-auto">
        <div className="flex justify-center py-16 px-4 text-justify">
          <article className="prose prose-stone prose-sm max-w-none content-center sm:prose lg:prose-lg xl:prose-xl">
            <MDXRemote {...content} components={components} />
          </article>
        </div>
      </div>
    </Layout>
  );
};

export default IndexPage;

export const getStaticProps: GetStaticProps = async (): Promise<
  GetStaticPropsResult<Props>
> => {
  const global = await getGlobalProps();
  const postsDirectory = join(process.cwd(), "content");
  const fullPath = join(postsDirectory, "mention-legales.mdx");
  const fileContent = readFileSync(fullPath, "utf8");
  const content = await serialize(fileContent);

  return { props: { ...global, content } };
};
