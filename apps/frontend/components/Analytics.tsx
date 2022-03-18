import Script from "next/script";

interface Config {
  ua: string;
  debug: boolean;
}

const config: Config = {
  ua: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_UA ?? "",
  debug: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG === "true",
};

export const Analytics = (): JSX.Element => {
  return (
    <>
      <Script
        strategy="afterInteractive"
        src={`https://www.googletagmanager.com/gtag/js?id=${config.ua}`}
      />
      <Script
        id="ga-script"
        strategy="afterInteractive"
        dangerouslySetInnerHTML={{
          __html: `
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '${config.ua}', {
              page_path: window.location.pathname,
            });
          `,
        }}
      />
    </>
  );
};
