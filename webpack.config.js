const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')
    
    // Entry configuration
    .addEntry('app', './assets/app.js') // Nom d'entrée JavaScript différent
    .addStyleEntry('app_css', './assets/styles/app.css') // Nom d'entrée CSS différent
    .addEntry('dashboard_layout', './assets/js/dashboard-layout.js')
    .addEntry('recharts', 'recharts')
    .addEntry('apexcharts', 'apexcharts')
    .addEntry('d3', 'd3')

    // Enable file splitting
    .splitEntryChunks()

    // Enable and configure PostCSS
    .enablePostCssLoader((options) => {
        options.postcssOptions = {
            plugins: [
                require('tailwindcss'),
                require('autoprefixer'),
            ],
        };
    })

    /*
     * Feature Configuration
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableReactPreset()
    .enableIntegrityHashes()
    .enableSingleRuntimeChunk()

    // Configure Babel - no need to add '@babel/preset-env' manually
    .configureBabel((babelConfig) => {
        // Encore already includes preset-env by default, no need to add it manually
       
        babelConfig.plugins.push('@babel/plugin-transform-react-jsx');

        babelConfig.plugins.push('@babel/plugin-proposal-class-properties');
    }, {
        useBuiltIns: 'usage',
        corejs: 3
        
    })
    

    // Uncomment below lines if you use Sass, TypeScript or React:
    // .enableSassLoader()
    // .enableTypeScriptLoader()
    // .enableReactPreset()

    // Uncomment below line if you're having problems with a jQuery plugin:
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
