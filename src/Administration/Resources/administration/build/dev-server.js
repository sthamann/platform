require('./check-versions')();

const fs = require('fs');
const opn = require('opn');
const express = require('express');
const webpack = require('webpack');
const openInEditor = require('launch-editor-middleware');
const proxy = require('http-proxy-middleware');
const utils = require('./utils');
const config = require('../config');

if (!process.env.NODE_ENV) {
    process.env.NODE_ENV = JSON.parse(config.dev.env.NODE_ENV);
}
const webpackConfig = process.env.NODE_ENV === 'testing'
    ? require('./webpack.prod.conf')
    : require('./webpack.dev.conf');

// default host + port where dev server listens for incoming traffic
const port = process.env.PORT || config.dev.port;
const host = process.env.HOST || config.dev.host;

// automatically open browser, if not set will be false
const autoOpenBrowser = !!config.dev.autoOpenBrowser;

const app = express();
const compiler = webpack(webpackConfig);

app.use('/api', proxy({ target: process.argv[2], changeOrigin: true }));

// Open files in phpstorm while using the dev mode, the sw-devmode-loader needs to be in place
app.use('/__open-in-editor', openInEditor(config.dev.editor));

const devMiddleware = require('webpack-dev-middleware')(compiler, {
    publicPath: webpackConfig.output.publicPath,
    hot: true,
    stats: 'minimal'
});

const hotMiddleware = require('webpack-hot-middleware')(compiler, {
    log: () => {}
});

// force page reload when html-webpack-plugin template changes
compiler.hooks.compilation.tap('vue-webpack-template-reload-after-html-changes', (compilation) => {
    compilation.hooks.htmlWebpackPluginBeforeHtmlProcessing.tapAsync('vue-webpack-template-reload-after-html-changes', (data, cb) => {
        hotMiddleware.publish({ action: 'reload' });
        cb();
    });
});

// handle fallback for HTML5 history API
app.use(require('connect-history-api-fallback')());

// serve webpack bundle output
app.use(devMiddleware);

// enable hot-reload and state-preserving
// compilation error display
app.use(hotMiddleware);

const pluginList = utils.getPluginDefinitions('var/config_administration_plugins.json', true);
const staticPaths = pluginList.reduce((accumulator, plugin) => {
    const assetPath = `/${plugin.viewPath}static/`;

    if (fs.existsSync(assetPath)) {
        accumulator.push({
            staticPath: `/${plugin.name.toLowerCase()}/static`,
            systemPath: assetPath
        });
    }

    return accumulator;
}, []);

// serve pure static assets, see https://github.com/webpack/webpack-dev-server/issues/200#issuecomment-139666063
staticPaths.splice(0, 0, {
    staticPath: '/administration/static',
    systemPath: './static'
});

// We may cause filename clobbers when the same filename will be found in the directories.
app.use('/static', express.static('./static'));
staticPaths.forEach((paths) => {
    app.use(paths.staticPath, express.static(paths.systemPath));
    app.use('/static', express.static(paths.systemPath));
});

const uri = `http://${host}:${port}`;

app.use('/api', proxy({ target: process.argv[2], changeOrigin: true, secure: false }));

console.log('# Compiling Webpack configuration');
console.log(`Environment: ${process.env.NODE_ENV}`);
console.log(`Dev server URI: ${uri}`);
console.log(`Assets static path: ${staticPaths.map(paths => paths.systemPath).join(', ')}`);
console.log(`Automatically open browser: ${autoOpenBrowser}`);
console.log();

console.log('# Starting hot module reloading dev server');

devMiddleware.waitUntilValid(() => {
    console.log(`Dev server listening at ${uri}\n`);
});

module.exports = app.listen(port, host, (err) => {
    if (err) {
        console.log(err);
        return false;
    }

    // when env is testing, don't need open it
    if (autoOpenBrowser && process.env.NODE_ENV !== 'testing') {
        opn(uri).catch(() => {
            console.warn('Browser could not be started');
        });
    }
});
