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



if ( !class_exists ('ALWPRT_TRANSLATE_WITH_LIBRETRANSLATE')) {

class ALWPRT_TRANSLATE_WITH_LIBRETRANSLATE extends ALWPRT_TRANSLATE_BASE implements ALWPRT_TRANSLATE_SYSTEM
{

      /***
     * libretranslate service
     * https://libretranslate.com/translate - API REQUIRED
     * https://libretranslate.de/translate - FREE SOURCE
     * There is low-cost commercial version 
     * Free version does not support html tags
     * */

  
        /**
   * Get API Key for current translate system
   * */ 
  public function get_API_key()
  {
      
      $this->system_api_key = '' ;
    
  }

 


        /***
         * Translate with current system
         * */ 

     public function translation ($str, $fromLang, $toLang) {

      $url = 'https://libretranslate.de/translate'; // FREE SOURCE
    
      //Initiate cURL.
      $ch = curl_init($url);
      
      $jsonData = array(
        'q' => $str,
        'source' => $fromLang,
        'target' => $toLang,
      );
      
      //Encode the array into JSON.
      $jsonDataEncoded = json_encode($jsonData);
      
  
      //Tell cURL that we want to send a POST request.
      curl_setopt($ch, CURLOPT_POST, 1);
  
      // get result
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      
      //Attach our encoded JSON string to the POST fields.
      curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
      
      //Set the content type to application/json
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
      
      //Execute the request
      $result = curl_exec($ch);
  
      $arr = json_decode($result);
      
      curl_close($ch);
      
      if ( strlen( $arr->translatedText) > 0 ) {
  
        return $arr->translatedText;
  
      }
  
      return '';
     }


    }
   

}