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


class ALWPRT_TRANSLATE_TABLE
{

    public $lang;

    public function __construct(){

        $this->lang = sanitize_text_field ( $_POST['lang'] );

        if ( isset( $_POST['wpr-lang'] ) ) 
        {

            $this->lang = sanitize_text_field ( $_POST['wpr-lang'] );
        
        }


    }

    public function translate_title ( $name, $prod_id ) {

        $a = $_POST [ 'lang' ];
            $c = $_POST [ 'draw' ];
            $b = '';
            foreach( $_POST as $key=>$avlue) {
                $b.= '-'.$key;
            }
            return $name . '- work #' . $a .'#'. $c . $b ;

    }


}