// crago.config.js
// see: https://github.com/sharegate/craco

const path = require("path");
const fs = require("fs");

const cracoBabelLoader = require("craco-babel-loader");
// Handle relative paths to sibling packages
const appDirectory = fs.realpathSync(process.cwd());
const resolvePackage = (relativePath) =>
  path.resolve(appDirectory, relativePath);

// craco.config.js
module.exports = {
  style: {
    postcss: {
      plugins: [require("tailwindcss"), require("autoprefixer")],
    },
  },
  plugins: [
    {
      plugin: cracoBabelLoader,
      options: {
        includes: [
          // No "unexpected token" error importing components from these lerna siblings:
          resolvePackage("../common"),
          resolvePackage("../models"),
        ],
      },
    },
  ],
};
