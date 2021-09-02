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

if ( !class_exists ('ALWPRT_TRANSLATE_BASE')) {

class ALWPRT_TRANSLATE_BASE 
{

      /***
     * Google translate service (package V2)
     * documentation: https://cloud.google.com/translate/docs/reference/libraries/v2/php
     * https://googleapis.github.io/google-cloud-php/#/docs/google-cloud/v0.153.0/translate/v2/translateclient
     * API KEY required 
     * */

    public $main_lang;
    public $translate_lang;
    public $system_api_key; // API Key by System
    public $available_langs;  // Get All Langs Set in System

    public function __construct ( $API ) {

    $available_lang = get_option ( 'alwpr_langs', array() );

    // set main lang
    foreach ( $available_lang as $key=>$value ) {

          // set first language as default
            $this->main_lang = $key;

            break;

    }

    // Set API of translation system
    $this->system_api_key = $API;


    $this->available_langs = array_keys(  $available_lang );

    }



    /**
 * Make translation from main lang to other lang
 * */ 
public function translate_from_main_lang ($str, $toLang) {

  $str = $this->translation ($str, $this->main_lang, $toLang);

  return $str;

}

  /**
   * Make translation from any lang to main lang
   * */ 
  public function translate_to_main_lang ( $str, $fromLang) {

      $str = $this->translation ($str, $fromLang, $this->main_lang);

      return $str;

  }

          /***
         * Translate with current system
         * */ 

        public function translation ($str, $fromLang, $toLang) {

          return $str;

        }


    }
   

}