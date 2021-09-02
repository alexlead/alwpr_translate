<?php


/**
 * Plugin Name: WP TRANSLATE
 * @function
 *  
 **/

  // Exit if accessed directly
  if ( ! defined( 'ABSPATH' ) ) {	
	exit;
}

class Alwpr_Translate_Menu_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'wpr-translate-widget',
			'description' => 'Widget adds list of avialable languages',
		);
		parent::__construct( 'wpr-translate', 'WPR Translate Menu', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget

		require_once( ALWPR_TRANSLATE_DIR . '/widgets/templates/select.php' );
	}


}