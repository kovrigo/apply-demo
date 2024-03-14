let mix = require('laravel-mix')

/*
mix
  .setPublicPath('dist')
  .js('resources/js/card.js', 'js')
  .sass('resources/sass/card.scss', 'css')
*/

mix.setPublicPath('dist')
    .js('resources/js/card.js', 'js')
    .webpackConfig({
        resolve: {
            alias: {
                '~~nova~~': path.resolve(__dirname, '../../nova/resources/js/')
            }
        }
    })  