const mix = require('laravel-mix');

// mix.webpackConfig({ devtool: "inline-source-map" });

mix
  // admin
  //.js('resources/js/admin.js', 'public/js/admin.js')
  // .sass('resources/sass/admin.scss', 'public/css/admin.css')

  // .styles('public/css/style.css', 'public/css/style.min.css')

  // front
  .js('resources/js/app.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css/app.css')

  // .sourceMaps();

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