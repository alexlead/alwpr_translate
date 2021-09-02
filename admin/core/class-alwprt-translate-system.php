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

class ALWPRT_TRANSLATE_SYSTEM 
{


  public function __construct () {

    add_action ( 'wpr_translate_system', array($this, 'register_meta_box' ) );

  }

  public function register_meta_box () {

    
    add_meta_box( 'translate_system', __('Select translate system', 'alwpr-translate'), array($this, 'translate_system_form'), 'wpr-translate-system',  'normal' );



  }


/**
* Prepare edit form for translation systems
* @get integer
* 
*/ 
  public function translate_system_form ( ) {
    
    global $translate_systems;

    $status = $translate_systems;


    require (ALWPR_TRANSLATE_DIR . '/admin/templates/translate_system_form.php');
    
  }
  

  /**
* Save translation systems
* @get integer
* 
*/ 
  
  public function save_translate_system () {

    global $translate_systems;

    if ( in_array ( $_POST['wpr_translate_system'],  $translate_systems ) ) {

      $opt_save = array ( 
          'name' => sanitize_text_field ( $_POST['wpr_translate_system'] ) ,
          'key_api' => sanitize_text_field ( $_POST['wpr_key_api'] ) ,
      );

      update_option ( 'alwpr_translate_system', $opt_save );

      add_action( 'admin_notices', array( $this, 'admin_successful_notice' ) );
  }



  }

// Notice about saving settings
  public function admin_successful_notice() {
   
    echo '<div class="notice notice-success is-dismissible">';
    echo  '<p>' . __( 'Translations system successfull saved!' , 'alwprt-translate' ) .'</p>';
    echo  '</div>';
     
    }



}