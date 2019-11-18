let mix = require('laravel-mix');
require('laravel-mix-purgecss');
require('laravel-mix-favicon');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.ts('resources/js/app.ts', 'public/js/main')
    .ts('resources/js/admin/admin.ts', 'public/js/main')
;

mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/error-illustrated.scss', 'public/css')
    .copyDirectory('resources/svg', 'public/svg')
    .copyDirectory('resources/favicon', 'public/favicon')
    .purgeCss(
        {
            // Your custom globs are merged with the default globs.
            globs: [
                path.join(__dirname, 'modules/**/*'),
                path.join(__dirname, 'vendor/jerodev/laravel-font-awesome/src/**/*'),
            ],
            whitelistPatterns: [/^svg-inline.*/, /^fa.*/, /^ql-font-serif$/],
        }
    )
//    .favicon()
// .extract([
//     'vue',
//     'buefy',
//     'axios',
//     'vue-axios',
//     '@fortawesome/fontawesome-svg-core',
//     '@fortawesome/free-brands-svg-icons',
//     '@fortawesome/free-regular-svg-icons',
//     '@fortawesome/free-solid-svg-icons',
//     // 'bulma-modal-fx',
//     'dropzone',
//     'vue2-dropzone',
// ])
;

// mix.webpackConfig({
//     output: {
//         chunkFilename: `js/chunks/[name].js${mix.inProduction() ? '?id=[chunkhash]' : ''}`,
//     },
// });

if (mix.inProduction()) {
    mix.version();
} else {
    mix.sourceMaps()
        .webpackConfig({devtool: 'source-map'})
}

mix.browserSync('localhost:8000');

require('laravel-mix-bundle-analyzer');

if (mix.isWatching()) {
    mix.bundleAnalyzer();
}
