<?php


/**
 * Plugin Name: WP TRANSLATE
 * @function
 *  
 **/


 namespace ALPWRT;

   // Exit if accessed directly
   if ( ! defined( 'ABSPATH' ) ) {	
	exit;
}

class ALWPRT_WIDGETS 
{

  public $widget_options;

  public function __construct () {

    $this->widget_options = array ('nav_menu');

  }

  public function widget_list_metabox(){
    
    add_meta_box( 'widget_list', __('Widget headers for translation', 'alwpr-translate'), array($this, 'widgets_list_form'), 'alwprt-widgets-list', 'normal' );
    
  }
 
  // term edit
  public function widget_edit_metabox(){
    
    add_meta_box( 'widget_translate', __('Widget header edit translation', 'alwpr-translate'), array($this, 'widget_edit_form'), 'alwprt-widgets-list', 'normal' );
 
  }

  /***
  * Get available languges for translation
  */
  public function widget_edit_form_langs () {


    $langs = array();

    $site_lang = get_locale();

    $available_lang = get_option ( 'alwpr_langs', array() );

    foreach ( $available_lang as $key=>$value ) {

          // site base language
          if ( $value['language'] ==  $site_lang ) {
    
            continue;
        
          }

          $langs[$key] = $value['english_name'] . '/' . $value['native_name'];
    }

    return $langs;


  }

/***
* Get translation of term
*/ 
  public function get_option_translation ( $block, $widget_id, $langs ) {

    $widget_translate = array();

    foreach ( $langs as $key=>$value ) {

      $widget_translate[ 'translate-' . $key ] = get_option( 'widget_' . $block . '_translate_' . $key , array() ) [ $widget_id ];

    }

    return $widget_translate;

  }


/**
* Prepare edit form for translation
* @get integer
* 
*/ 
  public function widget_edit_form ( ) {

    if ( !isset( $_GET[ 'wpr_edit_widget_id' ] ) || $_GET[ 'wpr_edit_widget_id' ] < 1 ) {

      return;

    } 

   
    if ( !isset ( $_GET[ 'widget_block' ] ) || ! in_array ( $_GET[ 'widget_block' ] , $this->widget_options) ) {

      return;
    }
    $widget_id = sanitize_key ( $_GET[ 'wpr_edit_widget_id' ] );

    $widget_block = sanitize_key ( $_GET[ 'widget_block' ] );
    
    $widget_name = get_option('widget_' . $_GET[ 'widget_block' ]  , array() ) [ $_GET[ 'wpr_edit_widget_id' ] ] [ 'title' ] ;
    
     $langs = $this->widget_edit_form_langs();

    $widget_translate = $this->get_option_translation( $widget_block, $widget_id, $langs );

    require (ALWPR_TRANSLATE_DIR . '/admin/templates/widget_form.php');
    
    
  }
  
  
  /***
  * Save Term Translations
  */
  public function widget_save_meta () {
    
    if ( !isset ( $_POST[ 'widget_id' ] ) || $_POST[ 'widget_id' ] < 1 ) {
      
      return;
      
    }

    if ( !isset ( $_POST[ 'widget_block' ] ) || !in_array( $_POST[ 'widget_block' ], $this->widget_options ) ) {
      
      return;
      
    }
    
    
    $widget_id = sanitize_key ( $_POST[ 'widget_id' ] );

    $widget_block = sanitize_key ( $_POST[ 'widget_block' ] );
    
    $langs = $this->widget_edit_form_langs();
    
    foreach( $_POST [ 'translate' ]  as $key=>$value ) {
      
      $arr = get_option( 'widget_' . $widget_block . '_translate_' . $key , array() );  

      if ( array_key_exists ( $key,  $langs ) ) {

        $arr [ $widget_id ] [ 'title' ] = sanitize_text_field ( $value );
        
        update_option ( 'widget_' . $widget_block . '_translate_' . $key  , $arr, 'no' );

        
        
      }
      
    }

    add_action( 'admin_notices', array( $this, 'admin_successful_notice' ) );
    
  } 

  

  public function admin_successful_notice() {
   
  echo '<div class="notice notice-success is-dismissible">';
  echo  '<p>' . __( 'Translations successfull updated!' , 'alwprt-translate' ) .'</p>';
  echo  '</div>';
   
  }


  /***
  * Prepare list of terms
  */ 
  
  public function widgets_list_form() {
    
    $widgets = $this->get_all_widgets();

    require (ALWPR_TRANSLATE_DIR . '/admin/templates/widgets_table.php');

  }

    /**
     * Get terms list
     * */ 

     public function get_all_widgets () {

      foreach ( $this->widget_options as $group ) {

        $arr[ $group ] = get_option('widget_' . $group , array());

      }


        return $arr;

     }

 /**
  * Get term ID by name
  **/
  public function get_term_id ( $name ) {

    global $wpdb;

    $id = $wpdb->get_row( "SELECT term_id FROM $wpdb->terms WHERE name = '" . $name . "'" );
    
    return $id->term_id;

  }


  /**
  * Get term translate from DB
  **/ 
  public function get_term_translate_from_DB ( $id ) {

    return get_term_meta( $id, 'translate-' . $this->translate_lang , true );

  }


}