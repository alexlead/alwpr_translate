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

class ALWPRT_POST_TRANSLATE_DUBLICATE 

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

    public function get_db_post_for_transaltion( $post_id ) {

        
        return get_post($post_id) ;
    
      }


      public function dublicate_post_for_translation( $post_parent, $lang ) {


          if ( !in_array( $lang, array_keys( $this->langs ) ) ) {

              return;

          }

          $translate = $this->get_db_post_for_transaltion ( $post_parent );

          $arr  [ 'post_type' ] = 'translate-'.$lang;

          $arr  [ 'post_parent' ] = $post_parent;
          
          $arr [ 'post_title' ] = $translate->post_title;
          
          $arr [ 'post_excerpt' ] = $translate->post_excerpt;
          
          $arr [ 'post_content' ] = $translate->post_content; 

          $arr  [ 'post_status' ] = 'publish' ;
    
          $arr  ['post_modified']   =  date('Y-m-d H:i:s');
    
         return wp_insert_post(  $arr  );

      }






}
