import Document, { Html, Head, Main, NextScript } from "next/document";

class MyDocument extends Document {
  render(): JSX.Element {
    return (
      <Html lang={"fr"}>
        <Head>
          <script
            data-partytown-config
            dangerouslySetInnerHTML={{
              __html: `
              partytown = {
                lib: "/_next/static/~partytown/",
                debug: true,
                forward: ["dataLayer.push"],
                logCalls: true,
                logGetters: true,
                logSetters: true,
                logImageRequests: true,
                logScriptExecution: true,
                logSendBeaconRequests: true,
                logStackTraces: true,
              };
            `,
            }}
          />
        </Head>
        <Main />
        <NextScript />
      </Html>
    );
  }
}

export default MyDocument;
