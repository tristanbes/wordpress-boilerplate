const rootPath = './web/app/themes/example';
const paths = {
  src: rootPath + '/src',
  dist: rootPath + '/dist',
  vendor: './node_modules',
};

// Full configuration schema is available at https://github.com/Yproximite/yProx-cli/blob/master/lib/options.ts
module.exports = {
  assets: {
    theme: [
      // Scripts
      {
        handler: 'js',
        src: paths.src + '/js/**/*.js',
        dest: paths.dist + '/js',
        concat: 'scripts.min.js',
        uglify: true,
      },

      // Styles
      {
        handler: 'sass',
        src: paths.src + '/sass/style.scss',
        dest: paths.dist + '/css',
        concat: 'style.min.css',
      },

      // Images
      {
        handler: 'image',
        src: paths.src + '/img/**/*',
        dest: paths.dist + '/img',
      },
    ],
    vendor: [
      // Scripts
      // {
      //  handler: 'js',
      //  src: [paths.vendor + '/<package>/dist/<package>.min.js'],
      //  dest: paths.dist + '/js',
      //  concat: 'vendors.min.js',
      //  uglify: true,
      // },
      // Styles
      // {
      //  handler: 'css',
      //  src: [paths.vendor + '/<package>/dist/<package>.min.css'],
      //  dest: paths.dist + '/css',
      //  concat: 'vendors.min.css',
      // },
    ],
  },
};
