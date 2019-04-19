const { mix } = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.ts', 'js/cosplayer.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'css/cosplayer.css');

if (mix.inProduction()) {
    mix.version();
}