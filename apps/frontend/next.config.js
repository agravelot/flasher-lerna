const withPWA = require("next-pwa");
const runtimeCaching = require("next-pwa/cache");

const withBundleAnalyzer = require("@next/bundle-analyzer")({
  enabled: process.env.ANALYZE === "true",
});

const withTM = require("next-transpile-modules")([
  "@flasher/common",
  "@flasher/models",
]); // pass the modules you would like to see transpiled

module.exports = withTM(
  (module.exports = withBundleAnalyzer(
    (module.exports = withPWA({
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
        ];
      },
      // future: { webpack5: true },
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
          "assets.jkanda.fr",
          "s3.fr-par.scw.cloud",
          "assets-jkanda.s3.fr-par.scw.cloud",
          "assets.blog.jkanda.s3.fr-par.scw.cloud",
        ],
      },
      poweredByHeader: false,
      compress: false,
    }))
  ))
);
