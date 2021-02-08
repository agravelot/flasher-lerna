const withPWA = require("next-pwa");
const runtimeCaching = require("next-pwa/cache");

const withBundleAnalyzer = require("@next/bundle-analyzer")({
  enabled: process.env.ANALYZE === "true",
});

module.exports = withBundleAnalyzer(
  (module.exports = withPWA({
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
      ],
    },
    poweredByHeader: false,
  }))
);
