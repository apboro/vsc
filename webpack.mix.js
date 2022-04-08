const mix = require('laravel-mix');

mix.setPublicPath('./public/');

/*
 |--------------------------------------------------------------------------
 | Login form assets
 |--------------------------------------------------------------------------
 */
mix
    .js('resources/js/login.js', 'js').vue()
    .js('resources/js/admin.js', 'js').vue()
    .sass('resources/css/app.scss', 'css')

    .webpackConfig(require('./webpack.config.js'));

if (mix.inProduction()) {
    mix.version();
}
