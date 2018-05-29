<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Creating widget for Google Map SRM
 */
class srmgmap_widget extends WP_Widget
{

    public $base_id = 'srmgmap_widget'; //widget id
    public $widget_name = 'Google Map SRM'; //widget name
    public $widget_options = array(
        'description' => 'Google Map SRM' //widget description
    );

    public function __construct()
    {
        parent::__construct(
			$this->base_id, 
			$this->widget_name, 
			$this->widget_options
		);
		
		add_action( 'widgets_init', function() { register_widget( 'srmgmap_widget' ); });
    }

    // Map display in front
    public function widget( $args, $instance )
    {
		$title = apply_filters( 'widget_title', $instance['title'] );

        extract( $args );
        extract( $instance );
        echo $before_widget;
		if ( ! empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
        echo do_shortcode( $instance['srmgmap_shortcode'] );
        echo $after_widget;
    }

    /**
     * Google Map Widget
     * @return String $instance
     */
    public function form( $instance )
    {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
        $text = ! empty( $instance['srmgmap_shortcode'] ) ? $instance['srmgmap_shortcode'] : esc_html__( '', 'text_domain' );
        ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: </label>
        </p>
		<p>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
            <label for="<?php echo $this->get_field_id( 'srmgmap_shortcode' ); ?>"> Enter Google Map Shortcode:</label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id( 'srmgmap_shortcode' ); ?>"
                   name="<?php echo $this->get_field_name( 'srmgmap_shortcode' ); ?>"
                   value='<?php echo esc_attr( esc_html( isset( $instance['srmgmap_shortcode'] ) ? $instance['srmgmap_shortcode']:'') ); ?>' type="text" class="widefat">
        </p>

        <?php
    }
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['srmgmap_shortcode'] = ( ! empty( $new_instance['srmgmap_shortcode'] ) ) ? $new_instance['srmgmap_shortcode']  : '';
		return $instance;
	}

}

$srmgmap = new srmgmap_widget();