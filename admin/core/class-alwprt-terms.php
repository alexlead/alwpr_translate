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

class ALWPRT_TERMS 
{


  public function __construct () {

    

  }

  public function terms_list_metabox(){
    
    add_meta_box( 'term_list', __('Terms list for translation', 'alwpr-translate'), array($this, 'terms_list_form'), 'alwprt-terms-list', 'normal' );
    
  }
 
  // term edit
  public function term_edit_metabox(){
    
    add_meta_box( 'term_translate', __('Term edit translation', 'alwpr-translate'), array($this, 'term_edit_form'), 'alwprt-terms-list', 'normal' );
 
  }

  /***
  * Get available languges for translation
  */
  public function term_edit_form_langs () {


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
  public function get_meta_translation ( $term_id, $langs ) {

    $term_meta = array();

    foreach ( $langs as $key=>$value ) {

      $term_meta[ 'translate-' . $key ] = get_term_meta( $term_id, 'translate-' . $key , true );

    }

    return $term_meta;

  }


/**
* Prepare edit form for translation
* @get integer
* 
*/ 
  public function term_edit_form ( ) {

    if ( !isset( $_GET[ 'wpr_edit_term_id' ] ) || $_GET[ 'wpr_edit_term_id' ] < 1 ) {

      return;

    } 

    $term_id = sanitize_key ( $_GET[ 'wpr_edit_term_id' ] );

    $term_name = get_term( $term_id )->name;
    
    $langs = $this->term_edit_form_langs();

    $term_meta = $this->get_meta_translation( $term_id, $langs );

    require (ALWPR_TRANSLATE_DIR . '/admin/templates/term_form.php');
    
    
  }
  
  
  /***
  * Save Term Translations
  */
  public function term_save_meta () {
    
    if ( !isset ( $_POST[ 'term_id' ] ) || $_POST[ 'term_id' ] < 1 ) {
      
      return;
      
    }
    
    
    $term_id = sanitize_key ( $_POST[ 'term_id' ] );
    
    $langs = $this->term_edit_form_langs();
    
    foreach( $_POST [ 'translate' ]  as $key=>$value ) {
      
        
      if ( array_key_exists ( $key,  $langs ) ) {
        
        update_term_meta ( $term_id, 'translate-' . $key , sanitize_text_field ( $value ) );

        
        
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
  
  public function terms_list_form() {
    
    $terms = $this->get_all_terms();

    require (ALWPR_TRANSLATE_DIR . '/admin/templates/terms_table.php');

  }

    /**
     * Get terms list
     * */ 

     public function get_all_terms () {

        $args = array (

            'taxonomy' => [ 'post_tag', 'category' , 'product_cat' ], 
            

         );


        $arr = get_terms( $args );

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