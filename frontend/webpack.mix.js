/**
 * Modules
 */
const mix = require('laravel-mix')

/**
 * Project proxy
 */
const proxy = 'http://localhost/your-site-dir'

mix
	/**
	 * SCSS/SASS transpiler
	 */
	.sass('assets/scss/index.scss', 'style.css')
	/**
     * CSS
     */
	//  .styles(['assets/css/libs/*.css'], 'assets/css/libs.css')
	/**
	 * JS transpiler
	 */
	// .scripts(['assets/js/libs/*.js'], 'public/js/vendor.js')
	/**
	 * JS watcher
	 */
	.js(['assets/js/app.js'], 'public/js')
	/**
	 * Configs
	 */
	.options({ processCssUrls: false })
	.disableNotifications()
	.setPublicPath('')
	.webpackConfig({
		output: {
			chunkFilename: 'public/chunks/[name].js',
		},
	})
