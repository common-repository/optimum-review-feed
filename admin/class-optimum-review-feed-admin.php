<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       coffeemugvn.com
 * @since      1.0.0
 *
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/admin
 * @author     CoffeemugVN <simon@coffeemugvn.com>
 */
class Optimum_Review_Feed_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/optimum-review-feed-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( 'renderjson-js', plugin_dir_url( __FILE__ ) . 'js/renderjson.js', null, $this->version, false );
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/optimum-review-feed-admin.js', array( 'jquery' ), $this->version, false );
		
		$options = get_option( 'opf_settings' );

		// js vars transfer to client
		$opf_data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		);

		if ( $options['opf_debug_mode'] ){
			$cache = Optimum_Review_Feed_Cache::get_instance();
        
			$opf_data['reviews'] = $cache->get_cache_reviews( $options );
		}

		wp_localize_script( $this->plugin_name, 'opf_data', $opf_data );
	}


	/**
	 * Adds an admin menu to Settings section.
	 */
	public function add_admin_menu(  ) {

		add_options_page( OPF_PLUGIN_NAME, OPF_PLUGIN_NAME, 'manage_options', 'optimum_review_feed', [$this, 'opf_options_page'] );
	}

	
	/**
	 * Gets the location list.
	 */
	public function get_location_list(){
		// get var from post object
		$api_key = esc_html( $_POST['api_key'] );

    	$locations = Optimum_Review_Feed_Cache::get_instance()->get_locations( $api_key );

		$items = array(
			'status' => 'ok'
		);

		$data = array();
    	if (! is_wp_error( $locations ) ){
    		$data[] = array( "text" => __( 'Select Location', 'opf'), "value" => 'default' );

	    	foreach ( $locations as $key => $location ) {
	    		$data[] = array( "text" => $location->location_name, "value" => $location->location_token );
	    	}
    		
    		$items['data'] = $data;
    	
    	} else {
    		$items['status'] = 'error';
    		$items['msg']    = $locations->get_error_message();
    	}
    	
    	
    	echo json_encode( $items );
    	die;
	}


	/**
	 * Settings/Option Page register fields
	 * @link Admin_Init Hook
	 */
	public function settings_init(  ) { 

		register_setting( 'opf_setting_page', 'opf_settings' );


		add_settings_section(
			'opf_setting_page_section', 
			__( '', 'opf' ), 
			[$this, 'opf_settings_section_callback'], 
			'opf_setting_page'
		);

		add_settings_field( 
			'opf_api_key', 
			__( 'Your API Token', 'opf' ), 
			[$this, 'opf_api_key_render'], 
			'opf_setting_page', 
			'opf_setting_page_section' 
		);

		add_settings_field( 
			'opf_location_id', 
			__( 'Your Location', 'opf' ), 
			[$this, 'opf_location_id_render'], 
			'opf_setting_page', 
			'opf_setting_page_section' 
		);

		add_settings_field( 
			'opf_request_show_star', 
			__( 'Minimum Star Rating', 'opf' ), 
			[$this, 'opf_request_show_star_render'], 
			'opf_setting_page', 
			'opf_setting_page_section' 
		);

		add_settings_field( 
			'opf_recent_most_review', 
			__( 'Number of reviews to display', 'opf' ), 
			[$this, 'opf_recent_most_review_render'], 
			'opf_setting_page', 
			'opf_setting_page_section' 
		);

		add_settings_field( 
			'opf_debug_mode', 
			__( 'Debug Mode', 'opf' ), 
			[$this, 'opf_debug_mode_render'], 
			'opf_setting_page', 
			'opf_setting_page_section' 
		);

		add_filter('admin_footer_text', [$this, 'opf_update_admin_footer_text'] );

	}

	/**
	 * Update footer text for 'thank you create wordpress'
	 *
	 * @param      string  $footer_text  The footer text
	 *
	 * @return     string  footer text
	 */
	function opf_update_admin_footer_text( $footer_text ){
		$footer_text = '<a href="https://optimumfeedback.com/" target="_blank">' .OPF_PLUGIN_NAME. '</a>';
		return $footer_text;
	}


	/**
	 * Render API Key Field HTML
	 */
	function opf_api_key_render(  ) { 

		$options = get_option( 'opf_settings' );
		?>
		<input type='text' name='opf_settings[opf_api_key]' value='<?php echo $options['opf_api_key']; ?>'>
		<?php

	}


	/**
	 * Render LocationID Field HTML
	 */
	function opf_location_id_render(  ) { 

		$options = get_option( 'opf_settings' );

		if ( !isset($options['opf_location_id']) 
			|| empty( $options['opf_location_id'] ) 
			|| $options['opf_location_id'] == '-1' 
			) :
		?>

		<input type='button' id='get_location_list' value='<?php echo _e( 'Gather List of Locations', 'opf' ); ?>'>
		<select name='opf_settings[opf_location_id]' id="location_list" style="display: none;">
		</select>

	<?php else :

		// get location from API server
		$locations = Optimum_Review_Feed_Cache::get_instance()->get_locations( $options['opf_api_key'] );

	?>
		<select name='opf_settings[opf_location_id]' id="location_list">
			<option value="-1"><?php _e( 'Select Location', 'opf' ) ?></option>
			<?php
				foreach ( $locations as $key => $location ) {
		    		$selected = ( $location->location_token == $options['opf_location_id'] ) ? " selected" : "";
					echo sprintf( '<option value="%s"%s>%s</option>', $location->location_token, $selected, $location->location_name );
		    	}
			?>
		</select>
		
		<?php
		endif;
	}

	/**
	 * Render for Stars field html
	 */
	function opf_request_show_star_render(  ) { 

		$options = get_option( 'opf_settings' );
		if ( empty( $options['opf_request_show_star'] ) ){
			$options['opf_request_show_star'] = 3;
		}
		?>
		
		<select name='opf_settings[opf_request_show_star]'>
			<?php for ( $i = 1; $i <= 5; $i++ ) {
				$selected = ( $i == $options['opf_request_show_star'] ) ? " selected" : "";
				echo sprintf( '<option value="%s"%s>%s</option>', $i, $selected, $i );
			}?>
		</select>
		<span>&nbsp;<?php _e('out of 5 stars','opf')?></span>
		<p><i><?php _e('The widget will only show reviews above the rating you choose.','opf')?></i></p>
		<?php

	}


	/**
	 * render html for field most recent review
	 */
	function opf_recent_most_review_render(  ) { 

		$options = get_option( 'opf_settings' );
		if ( empty($options['opf_recent_most_review']) ){
			$options['opf_recent_most_review'] = 10;
		}
		?>

		<select name='opf_settings[opf_recent_most_review]'>
		<?php for ( $i = 1; $i <= 20; $i++ ) {
				$selected = ( $i == $options['opf_recent_most_review'] ) ? " selected" : "";
				echo sprintf( '<option value="%s"%s>%s</option>', $i, $selected, $i );
			}?>
		</select>
		<span>&nbsp;<?php _e('most recent reviews','opf')?></span>
		<p><i>1 - 20</i></p>
		<?php
	}


	/**
	 * render html for debug mode - boolean
	 */
	function opf_debug_mode_render(  ) { 

		$options  = get_option( 'opf_settings' );
		?>

		<select name='opf_settings[opf_debug_mode]'>
			<option value="0" <?php selected( $options['opf_debug_mode'], 0 );?>>False</option>
			<option value="1" <?php selected( $options['opf_debug_mode'], 1 );?>>True</option>
		</select>
		<?php
	}
	

	/**
	 * render html for heading group opf_settings
	 */
	function opf_settings_section_callback(  ) {
		$options   = get_option( 'opf_settings' );
		$last_time = date('Y-m-d H:s', strtotime( get_option( 'opf_lasttime' ) ) );

		echo __( 'Last data request: ', 'opf' ) . $last_time;
	}


	/**
	 * Setting Page GUI Initialize handlers
	 */
	function opf_options_page(  ) { 
		$options  = get_option( 'opf_settings' );

		if ( isset( $_POST['delete-cache'] ) ){
			$cache = Optimum_Review_Feed_Cache::get_instance();
			$cache->release_all_cache();
		}

		$msg = '';
		if ( empty( get_option( 'opf_cache_keys' ) ) ){
			$msg = 'Cache clear now.';
		}

		?>
		<form action="options.php" method='post'>

			<h2><?php echo OPF_PLUGIN_NAME . __( ' Settings', 'opf' ) ?></h2>
			<p>In order to call the widget, add the shortcode [optimum-feedback] to any post or page where you wish to show the widget.</p>
			<p>Full video tutorial can be found at <a href="http://OptimumFeedback.com/reviewfeed-setup">http://OptimumFeedback.com/reviewfeed-setup</a>.</p>
			<?php
				// setting fields layout
				settings_fields( 'opf_setting_page' );

				do_settings_sections( 'opf_setting_page' );

				submit_button();
			?>
		</form>

		<form method='post'>
			<?php if ( $options['opf_debug_mode'] ) : ?>

				<h3><?php _e( 'Debug Mode', 'opf' ) ?></h3>
				
				<p class="btn-delete"><input name="delete-cache" id="delete-cache" class="button" value="Force Delete Cache" type="submit"></p>

				<?php if (! empty( $msg ) ){

					echo $msg;

				} else { ?>

					<h4><?php _e( 'Cache Data', 'opf' ) ?></h4>
					<div id="viewer"></div>

				<?php } ?>
			<?php endif; ?>
		</form>
		<?php

	}

}
