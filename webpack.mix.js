const mix = require('laravel-mix');

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


require('laravel-mix-tailwind');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    /*.postCss("resources/css/tailwind.css", "public/css", [
        require("tailwindcss")
    ])*/
    /*.sass('resources/sass/bootstrap.scss', 'public/css').sourceMaps()*/
    .sass('resources/sass/app.scss', 'public/css').tailwind();
