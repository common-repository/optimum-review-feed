<?php

/**
 * Custom call to cotnrol cache data.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/includes
 * @author     CoffeemugVN <simon@coffeemugvn.com>
 */
class Optimum_Review_Feed_Cache {

    /**
     * The timeout of cache data
     *
     * @since    1.0.0
     * @access   private
     * @var      int    $time_out    The hours expired of caching data
     */
    private $time_out = 12;

    /**
    * Holds class instance
    *
    * @access protected
    *
    * @var singleton_example_two
    */
    protected static $instance;

    /**
    * Protected constructor to prevent new instances.
    */
    protected function __construct(){
        //feel free to do stuff that should only happen once here.
    }

    /**
    * Get class instance
    *
    * @return singleton_example
    */
    public static function get_instance(){
        if( null === static::$instance ){
            static::$instance = new static();
        }

        return static::$instance;
    }

    
    /**
     * Gets the reviews from Cache.
     *
     * @param      boolean  $output_style  The output style True:Widget/False:Shortcode
     *
     * @return     HTML  The reviews.
     */
    public function get_reviews( $output_style ) {
        // get the settings from options
        $settings = get_option( 'opf_settings' );
        if ( ! isset( $settings ) || empty( $settings['opf_api_key'] ) ){
            return '';
        }

        // get reviews data from cache
        $reviews = $this->get_cache_reviews( $settings );

        $data = array(
            'shortcode_wrapper' => !$output_style,
            'reviews' => $reviews
        );

        //error_log( 'show reviews ' . date('d-m-y H:s') );

        // send variables to templateRender
        extract( $data );

        // start buffer
        ob_start();

        // render html in to buffer each request
        require ( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/optimum-review-feed-public-display.php');

        $review_feeder = ob_get_contents();

        // clean the buffer
        ob_end_clean();

        return $review_feeder;
    }


    /**
     * Gets the cache reviews by service token
     *
     * @param      Array  $settings  The setting options
     */
    public function get_cache_reviews( $settings ) {
        $cache_key = 'opf_reviews_' . $settings['opf_api_key'];

        $reviews = get_transient( $cache_key );

        if ( false === $reviews ) {
            // these code runs when there is no valid transient set
            
            // call remote to fetch data
            //$live_url    = 'https://optimumfeedback.com/api/v0.9/reviews';
            //$sandbox_url = plugins_url( 'data/reviews.json', dirname(__FILE__) );
            
            $api_url = OPF_API_LIVE_URL;
            if ( $settings['opf_debug_mode'] ){
                //$api_url = OPF_API_SANDBOX_URL;
            }

            
            $params = array(
                'token'    => $settings['opf_api_key'],
                'location' => $settings['opf_location_id'],
                'limit'    => $settings['opf_recent_most_review'],
                'min'      => $settings['opf_request_show_star']
            );

            // build parser url parametters
            $api_request = add_query_arg( $params, $api_url );

            $this->write_log( 'new request cache from ' . $api_request );

            // access API and get header
            $request = wp_safe_remote_get( $api_request );
            
            if ( $error = is_wp_error( $request ) ) {
                $this->write_log ( 'Error on API::' . $error );
                //return 'Error on API::' . $error;
                //return false; // Bail early
            }

            $body = wp_remote_retrieve_body( $request );

            // service retun null/empty
            if ( empty( $body ) ){

                return null;
            }
            // parse json to Array and transfer to template
            $reviews = json_decode( $body );

            // save new cache and update option last-time
            set_transient( $cache_key, $reviews, $this->time_out * HOUR_IN_SECONDS );
            update_option( 'opf_lasttime', date('YmdHs') );

            // update collection keys
            $caches = get_option( 'opf_cache_keys' );
            if ( empty($caches) ){
                $caches = $cache_key;
            } else {
                $caches = $caches . ',' . $cache_key;
            }
            update_option( 'opf_cache_keys', $caches );
        }

        //return cache
        return $reviews;
    }


    /**
     * remove cache setting from memory based on the token key
     *
     * @param      <type>  $token  The token
     */
    public function delete_cache_reviews( $token ){
        $cache_key = 'opf_reviews_' . $token;
        delete_transient( $cache_key );
    }

    /**
     * remove all cache setting from memory 
     *
     */
    public function release_all_cache(){
        $caches = get_option( 'opf_cache_keys' );
        $keys = explode( ',', $caches );

        foreach ( $keys as $key => $value ) {
            $this->write_log( 'deleting transient ' . $value );
            delete_transient( $value );
        }

        update_option( 'opf_cache_keys', '' );
    }

    /**
     * Gets the cache reviews by service token
     *
     * @param      Array  $settings  The setting options
     */
    public function get_locations( $api_key ) {
           
        $api_url = OPF_LOCATION_API_LIVE_URL;

        
        $params = array(
            'token'    => $api_key,
        );

        // build parser url parametters
        $api_request = add_query_arg( $params, $api_url );

        $this->write_log( 'new request location from ' . $api_request );

        // access API and get header
        $request = wp_safe_remote_get( $api_request );
        
        if ( $error = is_wp_error( $request ) ) {
            $this->write_log ( 'Error on LocatioAPI::' . $error );
            //return 'Error on API::' . $error;
            //return false; // Bail early
        }

        $body = wp_remote_retrieve_body( $request );

        // service retun null/empty
        if ( empty( $body ) ){

            return new WP_Error( 'locError', __( "Location empty", "opf" ) );
        }


        // parse json to Array and transfer to template
        $locations = json_decode( $body );
        
        if ( isset( $locations->res ) && !$locations->res ){
            return new WP_Error( 'locError', $locations->msg );
        }

    
        return $locations;
    }


    /**
     * Writes a log.
     *
     * @param      Object|String   $log    The log
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    private function write_log ( $log )  {
        if (! WP_DEBUG ) return false;
        
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        }
    }
}
