import Layout from "../components/Layout";
import Header from "../components/Header";
import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import { getGlobalProps, GlobalProps } from "../stores";
import { NextSeo } from "next-seo";
import { configuration } from "utils/configuration";

type Props = GlobalProps;

const NotFoundPage: NextPage<Props> = ({
  appName,
  socialMedias,
  profilePictureHomepage,
}: Props) => {
  const pageTitle = `${appName}: 404`;

  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={pageTitle}
        description={""}
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
      <div>
        <Header
          title={"Oops, tu t'est perdu?"}
          separatorClass="text-gray-300"
        />
      </div>
    </Layout>
  );
};

export default NotFoundPage;

export const getStaticProps: GetStaticProps = async ({
  params,
}): Promise<GetStaticPropsResult<Props>> => {
  const global = await getGlobalProps();

  return { props: { ...global }, revalidate: 60 };
};
