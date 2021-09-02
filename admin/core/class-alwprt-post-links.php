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

class ALWPRT_POST_LINKS 

{

    public $langs;
    public $base_lang;

    public function __construct()
    {
        // get available languages for translation
        $site_lang = get_locale();
        $available_lang = get_option ( 'alwpr_langs', array() );
    
        foreach ( $available_lang as $key=>$value ) {
    
              // site base language
              if ( $value['language'] ==  $site_lang ) {
                
                $this->base_lang [ $key ] = $value;
                
                continue;
            }
            
            $this->langs [ $key ] = $value ;

    
        }

    }

    public function get_db_translation_post( $post_parent, $lang ) {

        global $wpdb;
    
        $post_type = 'translate-' . $lang;
    
        $sql = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE 1=1 '; 
        
        $sql .= ' AND post_parent = ' .$post_parent ;
    
        $sql .= ' AND post_type = "' .$post_type .'"';

        $sql .= " AND post_status IN ( 'publish', 'private' )";
    
        $request = $wpdb->get_row($sql);
    
        return $request->ID ;
    
      }





}
