const mix = require('laravel-mix');

mix.setPublicPath('./public/');

/*
 |--------------------------------------------------------------------------
 | Login form assets
 |--------------------------------------------------------------------------
 */
mix
    .js('resources/js/login.js', 'js')
    .js('resources/js/admin.js', 'js')
    .js('resources/js/lead.js', 'js')
    .js('resources/js/lead-single.js', 'js')
    .vue()
    .sass('resources/css/app.scss', 'css')

    .webpackConfig(require('./webpack.config.js'));

if (mix.inProduction()) {
    mix.version();
}
