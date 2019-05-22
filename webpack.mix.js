const mix = require('laravel-mix');

mix
  //.js('resources/js/admin.js', 'public/js/admin.js')
  .sass('resources/sass/admin.scss', 'public/css/admin.css')
  // .styles('public/css/style.css', 'public/css/style.min.css')
;

mix.options({
  processCssUrls: false
});

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': __dirname + '/resources/js'
    },
  }
});