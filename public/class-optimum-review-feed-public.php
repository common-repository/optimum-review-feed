<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/public
 * @author     CoffeemugVN <simon@coffeemugvn.com>
 */
class Optimum_Review_Feed_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Optimum_Review_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Optimum_Review_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'swiper-style', plugin_dir_url( __FILE__ ) . 'css/swiper.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/optimum-review-feed-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Optimum_Review_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Optimum_Review_Feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	
		// swiper for sliders
		wp_enqueue_script( 'swiper-min', plugin_dir_url( __FILE__ ) . 'js/swiper.min.js', array( 'jquery' ), $this->version, false );
		
		// main logic plugin
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/optimum-review-feed-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Creates a reciew feed shortcode.
	 *
	 * @return     HTML  Review Feed HTML
	 */
	public function render_review_feeder() {
		// get data from cache
		$cache = Optimum_Review_Feed_Cache::get_instance();

   		return $cache->get_reviews( false );
	}
}