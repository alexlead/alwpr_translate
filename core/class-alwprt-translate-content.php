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

class ALWPRT_TRANSLATE_CONTENT 
{

  public $main_lang;
  public $translate_lang;

  public $translate_system;


  // set path for transate
  public function __construct($lang){


    // $site_lang = get_locale();
    $available_lang = get_option ( 'alwpr_langs', array() );

    foreach ( $available_lang as $key=>$value ) {

          // set first language as default
            $this->main_lang = $key;

            break;

    }

    // purpose language
    $this->translate_lang = $lang;

  }


  /**
   * get translation from DB
   * @get integer
   * @return Object
   * */ 
  public function get_db_translation_post( $post_parent ) {

    global $wpdb;

    $post_type = 'translate-'.$this->translate_lang;

    $sql = 'SELECT * FROM ' . $wpdb->posts . ' WHERE 1=1 '; 
    
    $sql .= ' AND post_parent = ' .$post_parent ;

    $sql .= ' AND post_type = "' .$post_type .'"';

    $sql .= " AND post_status IN ( 'inherit', 'publish', 'private' )";

    $request = $wpdb->get_row($sql);

    return $request ;

  }

  /**
   * Post translations
   * */ 

  public function get_translation ( $post_parent ) {


    $translate = $this->get_db_translation_post( $post_parent );

    if ( $translate->ID > 0 ) {

        $arr [ 'ID' ] = $translate->ID;
        $arr [ 'post_title' ] = $translate->post_title;
        $arr [ 'post_excerpt' ] = $translate->post_excerpt;
        $arr [ 'post_content' ] = $translate->post_content; 

    } else {

      $arr = $this->get_translation_from_API_service ( $post_parent );

        wp_insert_post( wp_slash( $arr ) );


    }

    return $arr;
    


  }

  public function get_page_content_translation ( $post_parent ) {

    $translate = $this->get_db_translation_post( $post_parent );

    if ( $translate->ID > 0 ) {

        $arr [ 'ID' ] = $translate->ID;
        $arr [ 'post_title' ] = $translate->post_title;
        $arr [ 'post_excerpt' ] = $translate->post_excerpt;
        $arr [ 'post_content' ] = $translate->post_content; 

    } else {

      $arr [ 'ID' ] = 0;

    }

    return $arr;
  }

/**
 * Order status translation
 * */ 

 public function get_status_translation ($status_id) {

  $translate = $this->get_db_translation_post( $status_id );

  if ( $translate->ID > 0 ) {

    $arr [ 'ID' ] = $translate->ID;
    $arr [ 'post_title' ] = $translate->post_title;
    $arr [ 'post_content' ] = $translate->post_content; 

} else {

  $arr = $this->get_status_translation_from_API_service ( $status_id );

    wp_insert_post( wp_slash( $arr ) );


}

return $arr;


 }

 /***
 * Automatic status translate
 * */  

public function get_status_translation_from_API_service ( $status_id ) {

  $parent_post_data = get_post( $status_id );

      $post_data [ 'post_type' ] = 'translate-'.$this->translate_lang;

      $post_data [ 'post_parent' ] = $status_id;

      $post_data [ 'post_title' ] = sanitize_post ( $this->get_translation_service ( $parent_post_data->post_title ) );

      $post_data [ 'post_content' ] = sanitize_post ( $this->get_translation_service ( $parent_post_data->post_content ) );

      $post_data [ 'post_status' ] = 'publish' ;

      $post_data ['post_modified']   =  date('Y-m-d H:i:s');

      return $post_data;

}


/***
 * Iteration ORDER STATUS GENERATE TRANSLATION 
 * */  

public function iteration_status_translation ( $status_id ) {

  require_once ( ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-post-links.php' );
      
  $alwprt_translate_links = new ALWPRT_POST_LINKS();


  foreach (  $alwprt_translate_links->langs as $key=>$value ) {

      $this->translate_lang = $key;

      $this -> generate_status_translation ($status_id, $key);

  }


}

/***
 * ORDER STATUS GENERATE TRANSLATION
 * */  

public function generate_status_translation ($status_id, $lang) {

  $translate = $this->get_db_translation_post( $status_id );

  if ( ! ( $translate->ID > 0 ) ) {

  $arr = $this->get_status_translation_from_API_service ( $status_id );

    wp_insert_post( wp_slash( $arr ) );


    }


 }



  /***
   * Prepare text translations from other sources
   * **/ 
  public function get_translation_from_API_service ( $post_parent ) {

      $parent_post_data = get_post( $post_parent );


      $post_data [ 'post_type' ] = 'translate-'.$this->translate_lang;

      $post_data [ 'post_parent' ] = $post_parent;

      $post_data [ 'post_title' ] = sanitize_post ( $this->get_translation_service ( $parent_post_data->post_title ) );

      $post_data [ 'post_excerpt' ] = sanitize_post ( $this->get_translation_service ( $parent_post_data->post_excerpt ) );

      $post_data [ 'post_content' ] = sanitize_post ( $this->get_translation_service ( $parent_post_data->post_content ) );

      $post_data [ 'post_status' ] = 'publish' ;

      $post_data ['post_modified']   =  date('Y-m-d H:i:s');

      return $post_data;

  }
 

  // menu name translations: get translation from DB
  public function get_menu_translation_from_db ( $post_parent ) {
  

    global $wpdb;

    $post_type = 'nav-translate-'.$this->translate_lang;

    $sql = 'SELECT ID, post_title FROM ' . $wpdb->posts . ' WHERE 1=1 '; 
    
    $sql .= ' AND post_parent = ' .$post_parent ;

    $sql .= ' AND post_type = "' .$post_type .'"';

    $sql .= " AND post_status IN ( 'inherit', 'publish', 'private' )";

    $request = $wpdb->get_row($sql);

    return $request ;


  }
  // menu title translation: select a way for translation
  public function get_menu_translation( $post_parent, $title ) {

    $translate = $this->get_menu_translation_from_db ( $post_parent );

    if ( $translate->ID > 0 ) {

      return $translate->post_title;

    } else {


      $post_data [ 'post_type' ] = 'nav-translate-'.$this->translate_lang;

      $post_data [ 'post_parent' ] = $post_parent;

      $post_data [ 'post_title' ] = sanitize_post ( $this->get_translation_service ( $title) );

      $post_data [ 'post_status' ] = 'publish' ;

      $post_data ['post_modified']   =  date('Y-m-d H:i:s');


      wp_insert_post( wp_slash( $post_data ) );

      return $post_data [ 'post_title' ];
    }



  }


    // menu name translations: get translation from DB
    public function get_acf_name_translation_from_db ( $post_parent ) {
  

      global $wpdb;
  
      $post_type = 'acf-translate-'.$this->translate_lang;
  
      $sql = 'SELECT ID, post_title FROM ' . $wpdb->posts . ' WHERE 1=1 '; 
      
      $sql .= ' AND post_parent = ' .$post_parent ;
  
      $sql .= ' AND post_type = "' .$post_type .'"';
  
      $sql .= " AND post_status IN ( 'inherit', 'publish', 'private' )";
  
      $request = $wpdb->get_row($sql);
  
      return $request ;
  
  
    }
    // menu title translation: select a way for translation
    public function get_acf_name_translation( $post_parent, $title ) {
  
      $translate = $this->get_acf_name_translation_from_db ( $post_parent );
  
      if ( $translate->ID > 0 ) {
  
        return $translate->post_title;
  
      } else {
  
  
        $post_data [ 'post_type' ] = 'acf-translate-'.$this->translate_lang;
  
        $post_data [ 'post_parent' ] = $post_parent;
  
        $post_data [ 'post_title' ] = sanitize_post ( $this->get_translation_service ( $title) );
  
        $post_data [ 'post_status' ] = 'private' ;
  
        $post_data ['post_modified']   =  date('Y-m-d H:i:s');
  
  
        wp_insert_post( wp_slash( $post_data ) );
  
        return $post_data [ 'post_title' ];
      }
  
  
  
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

  /**
  * Prepare translation of terms
  **/ 

  public function get_term_translation ( $name ) {

    $id = $this->get_term_id ( $name );

    if ( !($id > 0) ) {

      return '';

    }

    $translate = $this->get_term_translate_from_DB ( $id );

    if ( strlen ( $translate ) > 0 ) {

      return $translate;
      
    }
    
    $translate = $this->get_translation_service ( $name );
    
    update_term_meta( $id, 'translate-' . $this->translate_lang , $translate );
    
    return $translate;


  }

  /***
   * Select translation system
   * */ 
  public function get_translation_service ($str) {


    // -------------- return mistake cases -----------------
           // if it is empty string then return nothing
           if ( strlen( $str ) == 0 ) {

            return '';
      
           }
      
           // if there is shortcode then do not translate 
           $shortcode_pattern = '/\[.*?\]/';
      
           if ( preg_match( $shortcode_pattern , $str ) ) {
      
            return $str;
      
           }
      
           $shortcode_pattern = '/\[.*?\]/';
      
           if ( preg_match( $shortcode_pattern , $str ) ) {
      
            return $str;
      
           }
          // -------------- end: return mistake cases -----------------
      
          // SELECT TRANSLATION SYSTEM

          global $alwprt_translate;

           $str = $alwprt_translate->translate_from_main_lang( $str,  $this->translate_lang );

            return $str;
    
  }

  
  
  
  
}