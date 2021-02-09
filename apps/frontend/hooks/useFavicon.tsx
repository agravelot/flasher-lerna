import Head from "next/head";

interface FaviconHook {
  head: () => JSX.Element;
}

export const useFavion = (): FaviconHook => {
  const head = (): JSX.Element => (
    <Head>
      <link
        key="apple-touch-icon"
        rel="apple-touch-icon"
        sizes="180x180"
        href="/apple-touch-icon.png"
      />
      <link
        key="icon32"
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="/favicon-32x32.png"
      />
      <link
        key="icon16"
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="/favicon-16x16.png"
      />
      <link key="manifest" rel="manifest" href="/manifest.json" />
      <meta
        key="msapplication-TileColor"
        name="msapplication-TileColor"
        content="#000000"
      />
      <meta key="theme-color" name="theme-color" content="#ffffff" />
    </Head>
  );
  return { head };
};
