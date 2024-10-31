<?php
/**
 * The public-facing functionality of the widget.
 *
 * Defines the widget name, render html.
 *
 * @package    Optimum_Review_Feed
 * @subpackage Optimum_Review_Feed/includes
 * @author     CoffeemugVN <simon@coffeemugvn.com>
 */
class Optimum_Review_Feed_Widget extends WP_Widget {
    /**
     * Set up the widget name and description.
     */
    public function __construct() {

        $widget_options = array( 
            'classname'   => 'optimum_feedback_review_widget', 
            'description' => 'A list of reviews from OptimumFeedback' 
        );
        parent::__construct( 'optimum_feedback_review_widget', 'Optimum Feedback Reviews', $widget_options );
    }


    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        // render widget title - input from admin
        if ( ! empty( $instance['title'] ) ) {

            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        // get rendered html from cache
        $cache = Optimum_Review_Feed_Cache::get_instance();
        echo $cache->get_reviews( true );

        echo $args['after_widget'];
    }

    
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php 
    }


    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance[ 'title' ] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

}

// Register the widget.
function opf_register_review_widget() { 
    register_widget( 'Optimum_Review_Feed_Widget' );
}
add_action( 'widgets_init', 'opf_register_review_widget' );