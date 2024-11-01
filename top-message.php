<?php
/**
 * Plugin Name: Top Message by Ybus
 * Plugin URI: http://y-bus.com
 * Description: This plugin adds a new message above your site navigation
 * Version: 1.0.0
 * Author: Fernando Ybus
 * Author URI: http://y-bus.com
 * License: GPLv2 or later
 */


/////////////////////////////////////////////////////////////////////////////////////////////////////
//ACTIVATE///////////////////////////////////////////////////////////////////////////////////////////
register_activation_hook(__FILE__, "top_message_activate");

function top_message_activate(){

	error_log('plugin Top Message activated');
}

/////////////////////////////////////////////////////////////////////////////////////////////////////
//ADMIN INTERFACE/////////////////////////////////////////////////////////////////////////////////////////

function top_message_option_page (){

	echo '<div class="wrap">';
	screen_icon();
	echo '<h2>Top Message Options</h2>';
	echo '<p>Set your custom message here</p>';
	echo '<form action="options.php" method="post" id="top_message_options_form">';
	settings_fields('top_message_options');
	
	echo '<h3><label for="top_message">Insert message here</label></h3>';
	echo '<input type="text" id="top_message" name="top_message" value="' . esc_attr(get_option('top_message')) . '"/>';
	
	echo '<h3><label for="top_message">Insert color here</label></h3>';
	echo '<input type="color" id="top_message_color" name="top_message_color" value="' . esc_attr(get_option('top_message_color')) . '"/>';
	
	echo '<h3><label for="top_message">Insert bg color here</label></h3>';
	echo '<input type="color" id="top_message_bg_color" name="top_message_bg_color" value="' . esc_attr(get_option('top_message_bg_color')) . '"/>';
	echo '<p><input type="submit" name="submit" value="Save"/></p>';
	echo '</form>';
	echo '</div>';

}


function top_message_plugin_menu(){

	add_menu_page('Top Message Ybus', 'Top Message by Ybus', 'manage_options', 'top-message-plugin', 'top_message_option_page');


}

add_action('admin_menu', 'top_message_plugin_menu');


function top_message_init(){

	register_setting('top_message_options','top_message');
	register_setting('top_message_options','top_message_color');
	register_setting('top_message_options','top_message_bg_color');

}
add_action('admin_init', 'top_message_init');

/////////////////////////////////////////////////////////////////////////////////////////////////////
//AT THE TOP/////////////////////////////////////////////////////////////////////////////////////////


add_action( 'wp_head', 'top_message' );
function top_message() {
  
  $plurl = plugins_url( 'style.css', __FILE__ );
  wp_enqueue_style( 'top-message-css', $plurl, array(), null );


  $message = get_option( 'top_message', false );
  $color = get_option( 'top_message_color', false );
  $bg_color = get_option( 'top_message_bg_color', false );

  echo '<div class="row_top_message" style="color:' . $color . ';background-color:' . $bg_color . ';">';
  echo $message;
  echo '</div>';

}

/////////////////////////////////////////////////////////////////////////////////////////////////////
//SHORTCODE/////////////////////////////////////////////////////////////////////////////////////////

add_shortcode('top_message', 'top_message_on_page');

function top_message_on_page($atts, $cotent=null) {
  
  $plurl = plugins_url( 'style.css', __FILE__ );
  wp_enqueue_style( 'top-message-css', $plurl , array(), null );

  echo '<div class="row_top_message_on_page">';
  echo $atts['message'];
  echo '</div>';

}


/////////////////////////////////////////////////////////////////////////////////////////////////////
//WIDGET/////////////////////////////////////////////////////////////////////////////////////////////
// Creating the widget 
class top_message_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'top_message_widget', 

// Widget name will appear in UI
__('Top Message Widget', 'top_message_widget_domain'), 

// Widget description
array( 'description' => __( 'Sample widget based on Top_Message Tutorial', 'top_message_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . '<div class="row_top_message_on_page">' . $title . '</div>' . $args['after_title'];

// This is where you run the code and display the output

echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'top_message_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Message:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class top_message_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'top_message_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );


/////////////////////////////////////////////////////////////////////////////////////////////////////
//DEACTIVATE/////////////////////////////////////////////////////////////////////////////////////////
register_deactivation_hook(__FILE__, "top_message_activate");
function top_message_deactivate(){

	remove_shortcode('top_message', 'top_message_on_page');

    $plurl = plugins_url( 'style.css', __FILE__ );
  	wp_dequeue_style( 'top-message-css', $plurl , array(), null );

  	delete_option( 'top_message' );
  	delete_option( 'top_message_color' );
  	delete_option( 'top_message_bg_color' );
  	error_log('plugin Top Message deactivated');
}




?>