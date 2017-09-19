const webpack = require('webpack')
const path = require('path')

module.exports = {
  entry: {
    vendor: [
      'lodash'
    ]
  },
  output: {
    filename: '[name].dll.js',
    path: path.resolve('webpack', 'dlls'),
    library: '[name]'
  },
  plugins: [
    new webpack.DllPlugin({
      name: '[name]_[hash]',
      path: path.resolve('webpack', 'dlls', '[name].json')
    })
  ]
}
