import React, { FC, ReactNode } from "react";
import Head from "next/head";
import Navbar from "./Navbar";
import Footer from "./Footer";
import { SearchModal } from "./search/SearchModal";
import { SearchContextProvider } from "../contexts/AppContext";
import { useFavion } from "../hooks/useFavicon";
import { DefaultSeo, SocialProfileJsonLd } from "next-seo";
import { configuration } from "../utils/configuration";
import { SocialMedia } from "@flasher/models";

type Props = {
  children?: ReactNode;
  socialMedias: SocialMedia[];
  appName: string;
};

const Layout: FC<Props> = ({ children, socialMedias, appName }: Props) => {
  const { head } = useFavion();

  return (
    <>
      <DefaultSeo
        title={appName}
        openGraph={{
          type: "website",
          locale: "fr_FR",
          url: configuration.appUrl,
          site_name: appName,
        }}
        twitter={{
          handle: `@${configuration.twitter}`,
          site: `@${configuration.twitter}`,
          cardType: "summary_large_image",
        }}
      />
      <SocialProfileJsonLd
        type="Person"
        name={appName}
        url={configuration.appUrl}
        sameAs={socialMedias.map((sm) => sm.url)}
      />
      <Head>
        <meta charSet="utf-8" />
        <meta name="charset" content="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="application-name" content={appName} />
        <meta name="apple-mobile-web-app-title" content={appName} />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="default" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="mobile-web-app-capable" content="yes" />
        <meta name="msapplication-config" content="/browserconfig.xml" />
        <meta name="msapplication-TileColor" content="#2B5797" />
        <meta name="msapplication-tap-highlight" content="no" />
        <meta name="theme-color" content="#000000" />
        {/* <link
          key="preconnect-serif-font"
          rel="preload"
          href="/fonts/Caveat-VariableFont_wght.ttf"
          as="font"
          type="font/ttf"
        /> */}
        <link
          key="preconnect-google-ga"
          href="https://www.google-analytics.com"
          rel="preconnect"
        />
        <link
          key="preconnect-keycloak"
          href="https://accounts.jkanda.fr"
          rel="preconnect"
        />
      </Head>
      {head()}
      <SearchContextProvider>
        <header>
          <Navbar />
          <SearchModal />
        </header>
        <main>{children}</main>
        <footer>
          <Footer socialMedias={socialMedias} />
        </footer>
      </SearchContextProvider>
    </>
  );
};

export default Layout;
