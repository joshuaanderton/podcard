const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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

mix.js('resources/js/player.js', 'public/js')
   .js('resources/js/site.js', 'public/js')
   .sass('resources/sass/player.scss', 'public/css')
   .sass('resources/sass/site.scss', 'public/css')
   .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],
   })
   .sourceMaps()
   .webpackConfig({devtool: 'source-map'});
