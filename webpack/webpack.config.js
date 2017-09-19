const Webpack = require('webpack')
const LessPluginAutoPrefix = require('less-plugin-autoprefix')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const CleanCSSPlugin = require('less-plugin-clean-css')
const autoPrefixer = require('autoprefixer')

const path = require('path')

const env = process.env.NODE_ENV

const webpack = {
  entry: {
    main: path.resolve('src', 'main.js'),
    singles: path.resolve('src', 'singles.js'),
    archives: path.resolve('src', 'archives.js'),
    categories: path.resolve('src', 'categories.js')
  },
  output: {
    path: path.resolve('build'),
    publicPath: '/',
    filename: '[name].bundle.js',
    chunkFilename: '[name]-[chunkhash].js'
  },

  module: {
    rules: [
      {
        test: /\.less$/,
        use: ExtractTextPlugin.extract({
          use: [
            {loader: 'css-loader'},
            {
              loader: 'less-loader',
              options: {
                sourceMap: true,
                plugins: [
                  new LessPluginAutoPrefix(),
                  new CleanCSSPlugin({
                    advance: true,
                    level: 2
                  })
                ]
              }
            } // end less-loader
          ]
        })
      }, // end less rules
      {
        test: /\.scss$/,
        use: ExtractTextPlugin.extract({
          use: [
            {loader: 'css-loader'},
            {
              loader: 'postcss-loader',
              options: {
                plugins: () => [autoPrefixer]
              }
            },
            {loader: 'sass-loader'}
          ],
          fallback: 'style-loader'
        })
      }, // sass rules
      {
        test: /\.js$/,
        exclude: path.resolve('src'),
        enforce: 'pre',
        loader: 'source-map-loader'
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      },
      {
        test: /\.json$/,
        exclude: /node_modules/,
        loader: 'json-loader'
      },
      {
        test: /\.(png|jpg|woff|woff2|eot|ttf|svg)(\?.*)?$/,
        loader: 'file-loader?name=[path]/[name].[ext]?[hash]'
      }
    ] // end rules
  }, // end module

  plugins: [
    new Webpack.DllReferencePlugin({
      context: path.resolve('src'),
      name: '[name]',
      manifest: path.resolve('webpack', 'dlls', 'vendor.json')
    }),
    new Webpack.LoaderOptionsPlugin({
      minimize: (env === 'production'),
      debug: false
    }),
    new Webpack.optimize.UglifyJsPlugin({
      beautify: false,
      mangle: {
        screw_ie8: true,
        keep_fnames: true
      },
      compress: {
        screw_ie8: true
      },
      comments: false
    }),
    new ExtractTextPlugin({
      filename: '[name].style.css',
      allChunks: true
    })
  ]
}

if (env === 'production') {
  webpack.plugins.push(
    // More minification
    new Webpack.optimize.AggressiveMergingPlugin()
  )
}

module.exports = webpack
