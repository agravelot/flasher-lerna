import Document, { Html, Head, Main, NextScript } from "next/document";

class MyDocument extends Document {
  render(): JSX.Element {
    return (
      <Html lang={"fr"}>
          <Head >
              <link rel="stylesheet" href="https://use.typekit.net/rcn2jwe.css"/>
          </Head>
        <Main />
        <NextScript />
      </Html>
    );
  }
}

export default MyDocument;
