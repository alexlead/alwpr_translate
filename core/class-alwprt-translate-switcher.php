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


class ALWPRT_TRANSLATE_SWITCHER
{

public $locale; // locale languages default

public $alwprt_langs; // plugin language list

public $lang;

public $to_translate;

public function __construct () {

    $this->alwprt_langs = get_option ( 'alwpr_langs', array() );

    $this->locale = get_locale();


    foreach ($this->alwprt_langs as $key=>$value) {

        if ( $value [ 'language' ] ==  $this->locale ) {

            $this->lang = $key;

        }

    }


}


public function get_current_lang () {

    // save user lang option
    // $this->save_lang_by_user ();

        // get option from POST
        $this->get_lang_from_POST();

    
        // get option from URL
        $this->get_lang_from_GET();
        
    // get user option
    // $this->get_lang_from_user_options();



 

        $this->switch_to_lang_locale();

        return $this->lang;


}

// switch to lang 
public function switch_to_lang_locale () {
    
    $this->to_translate = false;


    if ( ! is_admin() ) {	// Apply user locale value to front-end.

      if ( $this->locale  !== $this->alwprt_langs [ $this->lang ] [ 'language' ]  ) {

        $this->to_translate = true;

        switch_to_locale( $this->alwprt_langs [ $this->lang ] [ 'language' ] );
      }
    }

    
}


public function save_lang_by_user () {

    if ( isset( $_GET[ 'switch' ] ) &&  $_GET[ 'switch' ]== 'switch'  ) {

        if  ( array_key_exists( $_GET [ 'lang' ]  ,  $this->alwprt_langs )  ) {

            
            if (  is_user_logged_in () ) {

                global $current_user;

                update_user_option ( $current_user->ID, 'alwpr-lang', sanitize_text_field ( $_GET [ 'lang' ] )  ) ;

            }

            $this->lang  = sanitize_text_field ( $_GET [ 'lang' ] ) ;

                    
        }

    }


}


// get lang ID from URL
public function get_lang_from_GET () {

    if ( isset ( $_GET [ 'lang' ] )  && strlen ( $_GET [ 'lang' ] ) < 5  ) {

        if  ( array_key_exists( $_GET [ 'lang' ]  ,  $this->alwprt_langs )  ) {

            $this->lang = sanitize_text_field ( $_GET [ 'lang' ] ) ;

        } 
  
      }

}
// get lang ID from POST URL
public function get_lang_from_POST () {

    if ( isset ($_POST [ 'wpr-lang' ] ) ) {

        $_POST [ 'lang' ] = $_POST [ 'wpr-lang' ];
    }  

    if ( isset ( $_POST [ 'lang' ] )  && strlen ( $_POST [ 'lang' ] ) < 5  ) {

        if  ( array_key_exists( $_POST [ 'lang' ]  ,  $this->alwprt_langs )  ) {

            $this->lang = sanitize_text_field ( $_POST [ 'lang' ] ) ;

        } 
  
      }

}


// get Lang ID from USER Local

public function get_lang_from_user_options () {


    global $current_user;

    if ( is_user_logged_in() ) { 

        $user_opt = get_user_option ( $current_user->ID, 'alwpr-lang');
        
        if ( strlen( $user_opt ) > 1 ) {
            
            $this->lang = $user_opt;

        }


    }


}








}