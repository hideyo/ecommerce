let mix = require('laravel-mix');

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


mix.js('resources/assets/js/frontend.js', 'public/js');
mix.js('resources/assets/js/backend.js', 'public/js');


mix.sass('resources/assets/sass/frontend/frontend.scss', 'public/css')
.sass('resources/assets/sass/backend/backend.scss', 'public/css/')
.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts')
.copy('node_modules/font-awesome/fonts', 'public/fonts')
.copy('node_modules/roboto-fontface/fonts', 'public/fonts')
.copy('resources/assets/images', 'public/images');


   