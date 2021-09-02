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


class ALWPRT_TRANSLATE_WIDGET
{

    public $lang;

    public $widget_options;

    public function __construct ( $lang ) {
        
        // get lang for translation
        $this->lang = $lang;

        // array of availible widgets
        $this->widget_options = array ('nav_menu');
     
    }

/**
 * Get Widget Title ID by Title
 * */ 
     public function get_translation_by_title ( $title ) {

        foreach ( $this->widget_options as $group ) {

            $arr = get_option('widget_' . $group , array());
    
            foreach ($arr as $key => $value){


                if ( $value[ 'title' ] == $title ) {

                    $translated = $this->get_title_translation( $group, $key );

                    if ( strlen ( $translated ) > 0 ) {

                        return $translated;

                    }

                }
            }


          }

          return $title;

     }


    /***
     * Get Title Translation
     * */  
    public function get_title_translation ( $group, $id ) {

        $arr = get_option( 'widget_' . $group . '_translate_' . $this->lang , array() );
    
        return $arr [ $id ] [ 'title' ];
    
    }



}