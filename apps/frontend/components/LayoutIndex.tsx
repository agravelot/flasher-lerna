import { SearchContext, SearchContextProvider } from "contexts/AppContext";
import React, { FunctionComponent, ReactNode, useContext } from "react";
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
  const context = useContext(SearchContext);
  if (!context) {
    throw new Error("Unable to get context");
  }
  const { open } = context;

  return (
    <>
      <SearchContextProvider>
          <Layout socialMedias={socialMedias} appName={appName}>
              {children}
          </Layout>
        </SearchContextProvider>
    </>
);
};

export default LayoutIndex;