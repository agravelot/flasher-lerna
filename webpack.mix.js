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

// TODO Add purge css without braking spacer helpers and admin table

mix.sass('resources/sass/error-illustrated.scss', 'public/css')
    .version();

mix.options({
    extractVueStyles: 'public/css/app.css',
}).version();

mix.ts('resources/js/app.ts', 'public/js')
    .ts('resources/js/admin/admin.ts', 'public/js')
    .js('resources/js/tinymce.js', 'public/js')
    .version()
    .extract([
        'vue',
        'buefy',
        'axios',
        'vue-axios',
        '@fortawesome/fontawesome-svg-core',
        '@fortawesome/free-brands-svg-icons',
        '@fortawesome/free-regular-svg-icons',
        '@fortawesome/free-solid-svg-icons',
        // 'bulma-modal-fx',
        'dropzone',
        'vue2-dropzone',
    ])
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