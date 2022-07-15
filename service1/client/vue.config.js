module.exports = {
  devServer: {
    port: 18080,
    proxy: 'http://localhost:8080',
    watchOptions: {
      poll: 1000
    },
    disableHostCheck: true
  },
  transpileDependencies: [
    'tough-cookie',
    'universalify',
  ]
}

