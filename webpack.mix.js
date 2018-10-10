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


mix.combine([
    './bootstrap',
    'node_modules/ekko-lightbox/dist/ekko-lightbox.js',
    'node_modules/bootstrap-validator/dist/validator.js',
	'resources/assets/js/frontend.js'
], 'public/js/frontend.js');



mix.combine([
    './bootstrap',
    'node_modules/bootbox/bootbox.js',
    'node_modules/datatables/media/js/jquery.dataTables.js',
    'node_modules/select2/dist/js/select2.js',
    'node_modules/jstree/dist/jstree.min.js',
    'node_modules/bootstrap-datepicker/js/bootstrap-datepicker.js',
    'node_modules/bootstrap-validator/dist/validator.js',
    'node_modules/summernote/dist/summernote.js',
	'resources/assets/js/backend.js'
], 'public/js/backend.js');





mix.sass('resources/assets/sass/frontend/frontend.scss', 'public/css')
.sass('resources/assets/sass/backend/backend.scss', 'public/css/')

.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts')
.copy('node_modules/font-awesome/fonts', 'public/fonts')
.copy('node_modules/roboto-fontface/fonts', 'public/fonts')
.copy('resources/assets/images', 'public/images');


   