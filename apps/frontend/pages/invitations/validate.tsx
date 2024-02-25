import Layout from "../../components/Layout";
import Header from "../../components/Header";
import { getGlobalProps, GlobalProps } from "../../stores";
import { GetStaticProps, GetStaticPropsResult, NextPage } from "next";
import { NextSeo } from "next-seo";
import dynamic from "next/dynamic";

type Props = GlobalProps;

const DynamicInvitationValidateComponent = dynamic(
  () =>
    import("../../components/InvitationValidateComponent").then(
      (mod) => mod.InvitationValidateComponent,
    ),
  { ssr: false },
);

const InvitationValidate: NextPage<Props> = ({
  appName,
  socialMedias,
}: Props) => {
  return (
    <Layout socialMedias={socialMedias} appName={appName}>
      <NextSeo
        title={`Validation invitation | ${appName}`}
        description="Valider mon invitation en tant que cosplayer"
        openGraph={{
          title: `Validation invitation | ${appName}`,
          description: "Valider mon invitation en tant que cosplayer",
        }}
      />
      <div>
        <Header>
          <div className="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
            <div className="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <DynamicInvitationValidateComponent />
            </div>
          </div>
        </Header>
      </div>
    </Layout>
  );
};

export default InvitationValidate;

export const getStaticProps: GetStaticProps = async (): Promise<
  GetStaticPropsResult<Props>
> => {
  const global = await getGlobalProps();

  return { props: { ...global } };
};
