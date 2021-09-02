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


interface ALWPRT_TRANSLATE_SYSTEM 
{

    
    /**
     * Make translation from main lang to other lang
     * */ 
    public function translate_from_main_lang ($str, $tolang);

    /**
     * Make translation from any lang to main lang
     * */ 
    public function translate_to_main_lang ($str, $fromlang);
        
    /***
     * Translate with current system
     * */ 
     public function translation ($str, $fromLang, $toLang);

    }
