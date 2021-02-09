module.exports = {
  siteUrl: process.env.APP_URL,
  generateRobotsTxt: true, // (optional)
  exclude: [],
  changefreq: "daily",
  priority: 0.7,
  transform: (config, url) => {
    return {
      loc: url,
      changefreq: config.changefreq,
      priority: config.priority,
      lastmod: config.autoLastmod ? new Date().toISOString() : undefined,
    };
  },
};
