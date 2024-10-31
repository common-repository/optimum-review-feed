<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              optimumfeedback.com
 * @since             1.0.0
 * @package           Optimum_Review_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       Optimum Review Feed
 * Plugin URI:        optimumfeedback.com
 * Description:       A plugin to create a widget/shortcode to get reviews data from Optimum Feedback and display on the website.
 * Version:           1.0.0
 * Author:            Optimum Feedback
 * Author URI:        optimumfeedback.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       optimum-review-feed
 * Domain Path:       /languages
 * Network:           true
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// declare constants
define( "OPF_PLUGIN_NAME", "Optimum Review Feed" );
define( "OPF_API_LIVE_URL", "https://optimumfeedback.com/api/v0.9/reviews" );
define( "OPF_API_SANDBOX_URL", plugins_url( 'data/reviews.json', __FILE__ ) );

define( "OPF_LOCATION_API_LIVE_URL", "https://optimumfeedback.com/api/v0.9/locations" );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-optimum-review-feed-activator.php
 */
function activate_optimum_review_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-optimum-review-feed-activator.php';
	Optimum_Review_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-optimum-review-feed-deactivator.php
 */
function deactivate_optimum_review_feed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-optimum-review-feed-deactivator.php';
	Optimum_Review_Feed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_optimum_review_feed' );
register_deactivation_hook( __FILE__, 'deactivate_optimum_review_feed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-optimum-review-feed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_optimum_review_feed() {

	$plugin = new Optimum_Review_Feed();
	$plugin->run();

}
run_optimum_review_feed();
