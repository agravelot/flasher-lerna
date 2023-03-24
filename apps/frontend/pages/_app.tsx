import { AppProps } from "next/app";
import { configuration } from "../utils/configuration";
import "../styles/main.css";
import { ReactElement } from "react";
import { Analytics } from "components/Analytics";
import { Clarity } from "components/Clarity";
import { setBaseUrl } from "@flasher/common";
import { SessionProvider } from "next-auth/react";

setBaseUrl(configuration.apiInternalUrl ?? configuration.apiUrl);

function App({
  Component,
  pageProps: { session, ...pageProps },
}: AppProps): ReactElement {
  return (
    <SessionProvider session={session}>
      <>
        <Analytics />
        <Clarity />
        <Component {...pageProps} />
      </>
    </SessionProvider>
  );
}

export default App;
