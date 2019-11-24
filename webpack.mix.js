const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/h5_apps/student_registration_app.js', 'public/js/h5')
    .sass('resources/sass/h5_apps/student_registration_app.scss', 'public/css/h5')
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
mix.copyDirectory(
    'resources/sass/smart/css',
    'public/assets/css'
);

mix.copyDirectory(
    'resources/js/smart/js',
    'public/assets/js'
);

mix.copy('resources/sass/smart/extra_page.css','public/css/extra_page.css');
mix.copy('resources/js/smart/extra_page.js','public/js/extra_page.js');