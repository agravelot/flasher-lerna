let mix = require('laravel-mix');
require('laravel-mix-purgecss');

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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/admin/admin.js', 'public/js')
    .js('resources/js/carousel.js', 'public/js')
    .js('resources/js/tinymce.js', 'public/js')
    .version()
    .extract();

mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/error-illustrated.scss', 'public/css')
    .version()
    .purgeCss();

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