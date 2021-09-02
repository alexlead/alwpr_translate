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

class ALWPRT_USER_REGISTRATION
{

  

  public function registration_list_metabox(){
    
    add_meta_box( 'ur_list', __('User Registration for translation', 'alwpr-translate'), array($this, 'ur_list_form'), 'alwprt-ur-list', 'normal' );
    
  }
 
  // term edit
  public function registration_edit_metabox(){
    
    add_meta_box( 'ur_translate', __('User Registration edit translation', 'alwpr-translate'), array($this, 'ur_edit_form'), 'alwprt-ur-list', 'normal' );
 
  }

  /***
  * Get available languges for translation
  */
  public function ur_edit_form_langs () {


    $langs = array();

    $site_lang = get_locale();

    $available_lang = get_option ( 'alwpr_langs', array() );

    foreach ( $available_lang as $key=>$value ) {

          // site base language
          if ( $value['language'] ==  $site_lang ) {
    
            continue;
        
          }

          $langs[$key]['lang'] = $value['english_name'] . '/' . $value['native_name'];
          $langs[$key]['flag'] = $value['flag'] ;
          
    }

    return $langs;


  }

  // get User Registration Form
public function get_parent_form($id) {

  return get_post( $id, ARRAY_A );

}

// get translation by parent ID and Lang
public function get_child_post($post_parent, $lang) {

  global $wpdb;

  $sql = "SELECT * FROM $wpdb->posts WHERE post_parent = $post_parent AND post_type = 'ur-translate-$lang'";

  return $wpdb->get_row( $sql , ARRAY_A );

}



/**
* Prepare edit form for translation
* @get integer
* 
*/ 
  public function ur_edit_form ( ) {

    if ( !isset( $_GET[ 'wpr_edit_ur_id' ] ) || $_GET[ 'wpr_edit_ur_id' ] < 1 ) {
      
      return;
      
    } 
    
    
    if ( !isset ( $_GET[ 'wpr_ur_lang' ] ) || ! array_key_exists ( $_GET[ 'wpr_ur_lang' ] , $this->ur_edit_form_langs() ) ) {
      
      return;
    }

    if ( isset ( $_POST[ 'abvvzcxnfhauaaerf' ] ) && wp_verify_nonce( $_POST[ 'abvvzcxnfhauaaerf' ], 'alwprt_user_registration_editor' )  ) {
      
      $this->ur_save_translation();
      
    }
    

    $ur_id = sanitize_key ( $_GET[ 'wpr_edit_ur_id' ] );

    $ur_lang = sanitize_key ( $_GET[ 'wpr_ur_lang' ] );
    
    $parent_form = $this->get_parent_form($ur_id)['post_content'];
    
    $translated_form = $this->get_child_post($ur_id, $ur_lang);


    require (ALWPR_TRANSLATE_DIR . '/admin/templates/ur_form.php');
    
    
  }
  
  
  /***
  * Save Term Translations
  */
  public function ur_save_translation () {
    
    
    if ( !isset ( $_POST[ 'wpr_form_ur_id' ] ) || $_POST[ 'wpr_form_ur_id' ] < 1 ) {
      
      return;
      
    }

    if ( !isset ( $_POST[ 'wpr_form_ur_lang' ] ) || ! array_key_exists ( $_POST[ 'wpr_form_ur_lang' ] , $this->ur_edit_form_langs() ) ) {
      
      return;
    
    }


    $arr = array (

      'post_parent' => sanitize_key ( $_POST[ 'wpr_form_ur_id' ] ),

      // 'post_type'  => 'user_registration-translate-' . sanitize_key ( $_POST[ 'wpr_form_ur_lang' ] ),
      'post_type'  => 'ur-translate-' . sanitize_key ( $_POST[ 'wpr_form_ur_lang' ] ),

      'post_status' => 'publish' ,

      'post_modified'   => date('Y-m-d H:i:s'),


    ) ;
       
    $arr [ 'post_content' ] =  $this->prepare_content_by_post( sanitize_key ( $_POST[ 'wpr_form_ur_id' ] ) ) ;
    
    add_action( 'admin_notices', array( $this, 'admin_successful_notice' ) );

if ( $_POST[ 'wpr_child_ur_id' ] > 0 ) {
  
  
  $arr ['ID'] = sanitize_key( $_POST[ 'wpr_child_ur_id' ] );
  

  return wp_update_post (  $arr  );
  
} else {
  
  return wp_insert_post (  $arr  );
      
      }


    

    return;

  } 

  
// prepare content
  public function prepare_content_by_post ( $post_parent ) {

    $parent_form = $this->get_parent_form($post_parent)['post_content'];

    $fields = ( json_decode ( $parent_form, true ) );
    

    foreach ( $fields [0] [0] as $key=>$value ) {
      
      if ( isset ( $_POST ['field'][ $value['field_key'] ] [ 'label' ] ) ) {

        $fields[0][0][$key]['general_setting']['label'] = sanitize_text_field ( $_POST ['field'][ $value['field_key'] ] [ 'label' ] );

      }

      if ( isset ( $_POST ['field'][ $value['field_key'] ] [ 'description' ] ) ) {

        $fields[0][0][$key]['general_setting']['description'] = sanitize_text_field ( $_POST ['field'][ $value['field_key'] ] [ 'description' ] );

      }

      if ( isset ( $_POST ['field'][ $value['field_key'] ] [ 'placeholder' ] ) ) {

        $fields[0][0][$key]['general_setting']['placeholder'] = sanitize_text_field ( $_POST ['field'][ $value['field_key'] ] [ 'placeholder' ] );

      }

    
    }

    return json_encode ( $fields, JSON_UNESCAPED_UNICODE );


  }



//  Notice for success operation
  public function admin_successful_notice() {
   
  echo '<div class="notice notice-success is-dismissible">';
  echo  '<p>' . __( 'Translations successfull updated!' , 'alwprt-translate' ) .'</p>';
  echo  '</div>';
   
  }


  /***
  * Prepare list of terms
  */ 
  
  public function ur_list_form() {
    
    $ur_forms = $this->get_all_ur();

    $langs = $this->ur_edit_form_langs();

    require (ALWPR_TRANSLATE_DIR . '/admin/templates/ur_table.php');

  }

    /**
     * Get terms list
     * */ 

     public function get_all_ur () {

      $args = array(
        'numberposts' => 0,
	      'category'    => 0,
        'post_type'   => 'user_registration',
      );

        $arr = get_posts($args);

        return $arr;

     }

 

}