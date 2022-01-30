const mix = require('laravel-mix');
const path = require('path');

/**
 * Browser sync and live reload.
 */
mix.browserSync({
    proxy: '127.0.0.1:8000',
    port: 3000,
    open: true,
});

/**
 * Custom aliases
 */
mix.alias({
    '@': path.resolve('./resources/js'),
});

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
   .vue({ version: 3 })
   .postCss('resources/css/app.css', 'public/css', [
       //
   ]);
