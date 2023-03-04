/* eslint-disable @typescript-eslint/no-var-requires */
const runtimeCaching = require("next-pwa/cache");

const { withSentryConfig } = require("@sentry/nextjs");
const path = require("path");

const withPWA = require("next-pwa")({
  dest: "public",
  disable: process.env.NODE_ENV !== "production",
  mode: process.env.NODE_ENV,
  runtimeCaching,
  fallbacks: {
    image: "/static/fallback.jpg",
    // document: '/other-offline',  // if you want to fallback to a custom page other than /_offline
    // font: '/static/font/fallback.woff2',
    // audio: ...,
    // video: ...,
  },
});

const withBundleAnalyzer = require("@next/bundle-analyzer")({
  enabled: process.env.ANALYZE === "true",
});

const withTM = require("next-transpile-modules")([
  "@flasher/common",
  "@flasher/models",
]); // pass the modules you would like to see transpiled

/** @type {Partial<import("@sentry/nextjs").SentryWebpackPluginOptions>} */
const sentryWebpackPluginOptions = {
  silent: true,
  dryRun: process.env.CI !== "true",
};

/**
 * @type {import("next").NextConfig}
 **/
const config = {
  output: "standalone",
  experimental: {
    // this includes files from the monorepo base two directories up
    // https://nextjs.org/docs/advanced-features/output-file-tracing#caveats
    outputFileTracingRoot: path.join(__dirname, "../../"),
  },
  pageExtensions: ["ts", "tsx", "js", "jsx", "md", "mdx"],
  async rewrites() {
    return [
      {
        source: "/robots.txt",
        destination: "/api/robots.txt",
      },
    ];
  },
  async redirects() {
    return [
      {
        source: "/contact",
        destination: "/#contact",
        permanent: true,
      },
      {
        source: "/albums",
        destination: "/galerie",
        permanent: true,
      },
      {
        source: "/albums/page/1",
        destination: "/galerie",
        permanent: true,
      },
      {
        source: "/albums/:slug",
        destination: "/galerie/:slug",
        permanent: false,
      },
      {
        source: "/categories",
        destination: "/categories/page/1",
        permanent: true,
      },
      {
        source: "/cosplayers",
        destination: "/cosplayers/page/1",
        permanent: true,
      },
      {
        source: "/blog/ae",
        destination: "/blog/le-respect-en-convention-ou-est-il-passe",
        permanent: true,
      },
    ];
  },
  reactStrictMode: true,
  env: {
    baseUrl: "baseUrl",
  },
  images: {
    domains: [
      "s3.fr-par.scw.cloud",
      "assets-jkanda.s3.fr-par.scw.cloud",
      "assets.blog.jkanda.s3.fr-par.scw.cloud",
    ],
    formats: ["image/avif", "image/webp"],
    minimumCacheTTL: 36000,
  },
  sentry: {
    hideSourceMaps: true,
  },
  poweredByHeader: false,
  compress: false,
};

module.exports = withSentryConfig(
  withTM(withBundleAnalyzer(withPWA(config))),
  sentryWebpackPluginOptions
);
