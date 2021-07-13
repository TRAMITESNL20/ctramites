const mix = require('laravel-mix');
let options = mix.options({})
let hmr = options.config.hmr
let webpack = require('webpack')
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let dotenvplugin = new webpack.DefinePlugin({
	'process.env': {
		APP_URL: JSON.stringify(process.env.APP_URL || null),
		SESSION_HOSTNAME: JSON.stringify(process.env.SESSION_HOSTNAME || null),
		PAYMENTS_HOSTNAME: JSON.stringify(process.env.PAYMENTS_HOSTNAME || null),
		PAYMENTS_KEY: JSON.stringify(process.env.PAYMENTS_KEY || null),
		NODE_ENV: JSON.stringify(process.env.NODE_ENV || null),
		TESORERIA_HOSTNAME: JSON.stringify(process.env.TESORERIA_HOSTNAME || null),
		APP_PREFIX: JSON.stringify(process.env.APP_PREFIX || null),
		INSUMOS_API_HOSTNAME: JSON.stringify(process.env.INSUMOS_API_HOSTNAME || null),
		INSUMOS_DOCS_HOSTNAME: JSON.stringify(process.env.INSUMOS_DOCS_HOSTNAME || null),
		INSUMOS_USERNAME: JSON.stringify(process.env.INSUMOS_USERNAME || null),
		INSUMOS_PASSWORD: JSON.stringify(process.env.INSUMOS_PASSWORD || null),
		ENTIDAD_REPOSITORIO:JSON.stringify(process.env.ENTIDAD_REPOSITORIO || null),
		TRAMITE_5_ISR:JSON.stringify(process.env.TRAMITE_5_ISR || null),
		TRAMITE_AVISO:JSON.stringify(process.env.TRAMITE_AVISO || null),
		TRAMITE_ACTANAC:JSON.stringify(process.env.TRAMITE_ACTANAC || null),
		TRAMITE_ACTADEF:JSON.stringify(process.env.TRAMITE_ACTADEF || null),
		TRAMITE_ACTAMAT:JSON.stringify(process.env.TRAMITE_ACTAMAT || null)
	}
})

mix.webpackConfig({
	plugins: [
		dotenvplugin,
	],
	output : {
		chunkFilename : "[name].[chunkhash].js",
        publicPath: process.env.MIX_ASSET_URL || "/"
	},
	devtool: 'source-map'
}).sourceMaps();

mix.js([
	"resources/js/app.js",
	"resources/js/changepassword.js",
	"resources/js/confirmpassword.js",
	"resources/js/login.js",
	"resources/js/recovery.js",
], 'public/js/bundle.js')
.sass('resources/sass/app.scss', 'public/css')
.options({
    processCssUrls: false
});

mix.copyDirectory('resources/images', 'public/images');
mix.copyDirectory('resources/js/pages', 'public/js/pages');
mix.copyDirectory('resources/js/plugins', 'public/plugins');
mix.copy('resources/js/scripts.bundle.min.js', 'public/js/scripts.bundle.min.js');
mix.copy('resources/js/scripts.bundle.js', 'public/js/scripts.bundle.js');
mix.copy('resources/media/misc/bg-1.jpg', 'public/media/misc/bg-1.jpg');

mix.copy('resources/js/jquery.min.js', 'public/js/jquery.min.js');
mix.copy('resources/js/popper.min.js', 'public/js/popper.min.js');
mix.copy('resources/js/jquery.dataTables.min.js', 'public/js/jquery.dataTables.min.js');
mix.copy('resources/js/dataTables.bootstrap4.min.js', 'public/js/dataTables.bootstrap4.min.js');

// mix.browserSync('localhost:8081');