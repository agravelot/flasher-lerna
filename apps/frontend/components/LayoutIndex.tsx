import { SearchContextProvider } from "contexts/AppContext";
import React, { FunctionComponent, ReactNode } from "react";
import Layout from "./Layout";
import { SocialMedia } from "@flasher/models";

type Props = {
    children?: ReactNode;
    socialMedias: SocialMedia[];
    appName: string;
  };

const LayoutIndex: FunctionComponent<Props> = ({
    children,
    socialMedias,
    appName,
  }: Props) => {
  return (
    <>
        <Layout socialMedias={socialMedias} appName={appName}>
          <SearchContextProvider>
            {children}
          </SearchContextProvider>
        </Layout>
    </>
);
};

export default LayoutIndex;