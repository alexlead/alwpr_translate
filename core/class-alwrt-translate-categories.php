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


class ALWPRT_TRANSLATE_CATEGORIES
{

    public $lang;

    public $to_translate;

    public function __construct ( $lang, $to_translate ) {
        
        $this->to_translate = $to_translate;


        $available_lang = get_option ( 'alwpr_langs', array() );


        if ( ! $this->to_translate ) {

            // setcookie ( 'wpr-lang',  NULL , -1, '/' );
            

            return;

        }



    }

}


// apply_filters( 'wc_product_table_data_categories', $this->get_product_taxonomy_terms( 'categories' ), $this->product );