const withPWA = require("next-pwa");
const runtimeCaching = require("next-pwa/cache");

const withBundleAnalyzer = require("@next/bundle-analyzer")({
  enabled: process.env.ANALYZE === "true",
});

const withTM = require("next-transpile-modules")([
  "@flasher/common",
  "@flasher/models",
]); // pass the modules you would like to see transpiled

const withMDX = require("@next/mdx")({
  extension: /\.mdx?$/,
  options: {
    remarkPlugins: [],
    rehypePlugins: [],
    // If you use `MDXProvider`, uncomment the following line.
    // providerImportSource: "@mdx-js/react",
  },
});

module.exports = withMDX((withTM(
  (module.exports = withBundleAnalyzer(
    (module.exports = withPWA({
      pageExtensions: ["ts", "tsx", "js", "jsx", "md", "mdx"],
      async redirects() {
        return [
          {
            source: "/contact",
            destination: "/#contact",
            permanent: true,
          },
          {
            source: "/albums",
            destination: "/albums/page/1",
            permanent: true,
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
      pwa: {
        dest: "public",
        runtimeCaching,
        disable: process.env.NODE_ENV !== "production",
        mode: process.env.NODE_ENV,
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
        formats: ["image/avif", "image/webp"]
      },
      poweredByHeader: false,
      compress: false,
    }))
  ))
)));
