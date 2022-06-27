import { AppProps } from "next/app";
import { configuration } from "../utils/configuration";
import "../styles/main.css";
import { ReactElement } from "react";
import { Analytics } from "components/Analytics";
import { Clarity } from "components/Clarity";
import { setBaseUrl } from "@flasher/common";
import { AuthenticationProvider } from "hooks/useAuthentication";

setBaseUrl(configuration.baseUrl);

function App({ Component, pageProps }: AppProps): ReactElement {
  return (
    <AuthenticationProvider
      keycloakConfig={configuration.keycloak}
      keycloakInitOptions={{
        enableLogging: process.env.NODE_ENV !== "production",
      }}
    >
      <>
        <Analytics />
        <Clarity />
        <Component {...pageProps} />
      </>
    </AuthenticationProvider>
  );
}

export default App;
