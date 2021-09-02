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

if ( !class_exists ('ALWPRT_TRANSLATE_WITH_GOOGLE')) {

class ALWPRT_TRANSLATE_WITH_GOOGLE extends ALWPRT_TRANSLATE_BASE implements ALWPRT_TRANSLATE_SYSTEM  
{

      /***
     * Google translate service (package V2)
     * documentation: https://cloud.google.com/translate/docs/reference/libraries/v2/php
     * https://googleapis.github.io/google-cloud-php/#/docs/google-cloud/v0.153.0/translate/v2/translateclient
     * API KEY required 
     * */



        /***
         * Translate with current system
         * */ 

     public function translation ($str, $fromLang, $toLang) {

      require_once ( ALWPR_TRANSLATE_DIR . '/vendor/autoload.php' );
    
  
      $translate = new Google\Cloud\Translate\V2\TranslateClient( array (
        'key' => $this->system_api_key,
      ) );
    
    // Translate text from english to french.
        $result = $translate->translate( $str, [
      
            'target' => $toLang,
            'format' => 'html',
        ]);
        
        return $result['text'];

     }


    }
   

}