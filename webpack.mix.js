let mix = require('laravel-mix');
require('laravel-mix-purgecss');
require('laravel-mix-merge-manifest');

// Allow multiple Laravel Mix applications
mix.mergeManifest();

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
    .js('resources/js/admin/tinymce.js', 'public/js/main')
;

mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/error-illustrated.scss', 'public/css')
        .purgeCss(
        {
            // Your custom globs are merged with the default globs.
            globs: [
                path.join(__dirname, 'modules/**/*'),
                path.join(__dirname, 'vendor/jerodev/laravel-font-awesome/src/**/*'),
            ],
        }
    )
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
}

mix.browserSync('localhost:8000');

require('laravel-mix-bundle-analyzer');

if (mix.isWatching()) {
    mix.bundleAnalyzer();
}
