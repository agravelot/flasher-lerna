import { Metadata } from "next";
// import "./globals.css";
import { ReactNode } from "react";

/**
 * Default metadata.
 *
 * @see https://nextjs.org/docs/app/api-reference/file-conventions/metadata
 */
export const metadata: Metadata = {
  title: "admin",
  description: "toot",
};

/**
 * The homepage root layout.
 *
 * @see https://nextjs.org/docs/app/api-reference/file-conventions/layout
 */
export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en">
      <head />
      {/*<SessionProvider session={session}>*/}
      <body>
        {/*<Header />*/}
        <main>{children}</main>
        {/*<Footer />*/}
      </body>
      {/*</SessionProvider>*/}
    </html>
  );
}
