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
mix.ts('resources/js/app.ts', 'public/js')
    .ts('resources/js/admin/admin.ts', 'public/js')
    .js('resources/js/admin/tinymce.js', 'public/js')
;

mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/error-illustrated.scss', 'public/css')
    .version()
    .purgeCss(
        {
            // Your custom globs are merged with the default globs. If you need to
            // fully replace the globs, use the underlying `paths` option instead.
            globs: [
                path.join(__dirname, 'modules/**/*'),
                path.join(__dirname, 'node_modules/buefy/**/*'),
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

mix.options({
    autoprefixer: {
        options: {
            browsers: [
                'last 2 versions',
            ]
        }
    }
});

mix.browserSync('localhost:8000');