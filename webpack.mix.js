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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/smart/smart_basic.js', 'public/js')
    .sass('resources/sass/smart/smart_basic.scss', 'public/css');

/*
*   拷贝图片目录, js css 文件等
*/
mix.copyDirectory(
    'resources/sass/smart/fonts',
    'public/assets/fonts'
);
mix.copyDirectory(
    'resources/sass/smart/img',
    'public/assets/img'
);
mix.copyDirectory(
    'resources/sass/smart/plugins',
    'public/assets/plugins'
);

mix.copy('resources/sass/smart/extra_page.css','public/css/extra_page.css');
mix.copy('resources/js/smart/extra_page.js','public/js/extra_page.js');