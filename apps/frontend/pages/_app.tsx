import { AppProps } from "next/app";
import { ReactKeycloakProvider } from "@react-keycloak/web";
import { configuration } from "../utils/configuration";
import "../styles/main.css";
import { ReactElement } from "react";
import { Analytics } from "components/Analytics";
import { Clarity } from "components/Clarity";
import { setBaseUrl } from "@flasher/common";
import Keycloak from "keycloak-js";

const keycloak = Keycloak();

setBaseUrl(configuration.baseUrl);

function App({ Component, pageProps }: AppProps): ReactElement {
  return (
    <ReactKeycloakProvider
      initOptions={{
        enableLogging: process.env.NODE_ENV !== "production",
      }}
      authClient={keycloak}
      // keycloakConfig={configuration.keycloak}
    >
      <>
        <Analytics />
        <Clarity />
        <Component {...pageProps} />
      </>
    </ReactKeycloakProvider>
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
