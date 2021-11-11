import { AppProps } from "next/app";
import { SSRKeycloakProvider, SSRCookies } from "@react-keycloak/ssr";
import { configuration } from "../utils/configuration";
import "../styles/main.css";
import { ReactElement } from "react";
import { Analytics } from "components/Analytics";
import { Clarity } from "components/Clarity";

function App({ Component, pageProps }: AppProps): ReactElement {
  return (
    <SSRKeycloakProvider
      initOptions={{
        enableLogging: process.env.NODE_ENV !== "production",
      }}
      keycloakConfig={configuration.keycloak}
      persistor={SSRCookies({})}
    >
      <Analytics />
      <Clarity />
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
