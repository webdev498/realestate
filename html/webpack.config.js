// webpack.config.js

var webpack = require('webpack');
var path = require('path');

var root = 'static/js';

module.exports = {
    entry: {
      app: path.resolve(__dirname, root + '/app.js'),
      search: path.resolve(__dirname, root + '/search.js')
    },
    output: {
        path: path.resolve(__dirname, root + '/bundle'),
        filename: '[name].js',
        pathinfo: false
    },
    resolve: {
        root: [path.resolve('components')],
        modulesDirectories: ['node_modules', 'components'],
        extensions: ['', '.js', '.jsx']
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /(node_modules)/,
                loader: 'babel',
                query: {
                    presets: ['react', 'es2015', 'stage-1'],
                    plugins: ['transform-runtime'],
                    cacheDirectory: true,
                 }
            },
            {test: /\.css$/, loaders: ['style', 'css']},
        ]
    },
    externals: {
        'jquery': '$'
    },
    plugins: [
        new webpack.optimize.DedupePlugin(),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        }),
        new webpack.IgnorePlugin(/^\.\/locale$/, [/moment$/])
        //new webpack.DefinePlugin({ 'process.env': { 'NODE_ENV': JSON.stringify('production') } })
    ]
};
