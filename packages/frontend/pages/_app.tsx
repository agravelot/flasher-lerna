import { AppProps } from "next/app";
import { SSRKeycloakProvider, SSRCookies } from "@react-keycloak/ssr";
import { configuration } from "../utils/configuration";
import "../styles/main.css";
import { ReactElement, useEffect } from "react";
import { useAnalytics } from "../hooks/useAnalytics";
import { useRouter } from "next/dist/client/router";

function App({ Component, pageProps }: AppProps): ReactElement {
  const { initialize, pageView } = useAnalytics();
  const router = useRouter();

  useEffect(() => {
    initialize();
    pageView();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  useEffect(() => {
    // Listen for page changes after a navigation or when the query changes
    router.events.on("routeChangeComplete", pageView);
    return () => {
      router.events.off("routeChangeComplete", pageView);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [router.events]);

  return (
    <SSRKeycloakProvider
      initOptions={{
        enableLogging: process.env.NODE_ENV !== "production",
      }}
      keycloakConfig={configuration.keycloak}
      persistor={SSRCookies({})}
    >
      <Component {...pageProps} />
    </SSRKeycloakProvider>
  );
}

// export const reportWebVitals = (metric: NextWebVitalsMetric): void => {
// console.log(metric);
// window.gtag("event", name, {
// 	event_category:
//     label === "web-vital" ? "Web Vitals" : "Next.js custom metric",
// 	value: Math.round(name === "CLS" ? value * 1000 : value), // values must be integers
// 	event_label: id, // id unique to current page load
// 	non_interaction: true, // avoids affecting bounce rate.
// });
// };

export default App;
