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

mix.js('resources/js/app.js', 'public/assets/js')
    .vue().version()
    /*.postCss("resources/css/tailwind.css", "public/css", [
        require("tailwindcss")
    ])*/
    /*.sass('resources/sass/bootstrap.scss', 'public/css').sourceMaps()*/
    .styles([ 'resources/css/iconsax.css'], 'public/assets/css/iconsax.css').version()
    .sass('resources/sass/app.scss', 'public/assets/css').tailwind().version();
