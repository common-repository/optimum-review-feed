<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       coffeemugvn.com
 * @since      1.0.0
 *
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/includes
 * @author     CoffeemugVN <simon@coffeemugvn.com>
 */
class Optimum_Review_Feed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'optimum-review-feed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
