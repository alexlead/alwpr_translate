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


if ( !class_exists ('ALWPRT_TRANSLATE_WITH_WHATSMATE')) {


class ALWPRT_TRANSLATE_WITH_WHATSMATE extends ALWPRT_TRANSLATE_BASE implements ALWPRT_TRANSLATE_SYSTEM
{

    /***
     * Whatsmate
     * https://www.whatsmate.net/translation-api.html
     * help instruction: 
     * https://whatsmate.github.io/2016-08-19-translate-text-php/
     * 
     * service supports html tags
     * Supported languages: https://api.whatsmate.net/v1/translation/supported-codes
     * */ 
    

    
/***
 * Check some rules by translate system
 * 
 * */ 

   public function check_translate_lang_with_system_rules ( $lang ) {

    if ( $lang == 'zh') {

        $lang = 'zh-CN';
  
       }

       return $lang;

     }



        
        /***
         * Translate with current system
         * */ 

     public function translation ($str, $fromLang, $toLang) {


        $fromLang = $this->check_translate_lang_with_system_rules ( $fromLang );

        $toLang = $this->check_translate_lang_with_system_rules ( $toLang );

            // When you have your own client ID and secret, put them down here:
            $CLIENT_ID = "FREE_TRIAL_ACCOUNT";
            $CLIENT_SECRET = "PUBLIC_SECRET";

            // Specify your translation requirements here:
            $postData = array(
                'fromLang' => $fromLang,
                'toLang' => $toLang,
                'text' => $str
            );

                $headers = array(
                'Content-Type: application/json',
                'X-WM-CLIENT-ID: '.$CLIENT_ID,
                'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
                );

                $url = 'http://api.whatsmate.net/v1/translation/translate';
                $ch = curl_init($url);

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

                $response = curl_exec($ch);

                curl_close($ch);

        return $response;
     }


    }
   

}