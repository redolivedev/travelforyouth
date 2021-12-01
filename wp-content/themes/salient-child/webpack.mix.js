// webpack.mix.js

let mix = require('laravel-mix');

mix.js('src/app.js', 'js')
   .sass('src/app.scss', 'css')
   .setPublicPath('dist');

// mix.js('src/app.js', 'js')
//    .autoload({
//        jquery: ['$', 'window.jQuery']
//     });
    
mix.browserSync('https://local.travelforyouth.com');


