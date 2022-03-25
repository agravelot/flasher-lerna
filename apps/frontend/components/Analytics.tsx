import { useRouter } from "next/router";
import Script from "next/script";
import { useEffect } from "react";

interface Config {
  ua: string;
  debug: boolean;
}

const config: Config = {
  ua: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_UA ?? "",
  debug: process.env.NEXT_PUBLIC_GOOGLE_ANALYTICS_DEBUG === "true",
};

export const Analytics = (): JSX.Element => {
  const router = useRouter();
  useEffect(() => {
    const handleRouteChange = (url: string) => {
      window.gtag("config", config.ua, {
        page_path: url,
      });
    };
    router.events.on("routeChangeComplete", handleRouteChange);
    return () => {
      router.events.off("routeChangeComplete", handleRouteChange);
    };
  }, [router.events]);

  return (
    <>
      <Script
        strategy="worker"
        src={`https://www.googletagmanager.com/gtag/js?id=${config.ua}`}
      />
      <Script
        id="ga-script"
        strategy="worker"
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
