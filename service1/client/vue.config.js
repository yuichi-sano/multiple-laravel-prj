module.exports = {
  devServer: {
    port: 18080,
    proxy: 'http://localhost:8080'
  },
  transpileDependencies: [
    'tough-cookie',
    'universalify',
  ]
}

