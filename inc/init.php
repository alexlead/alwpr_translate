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


// set default language


require_once ABSPATH . 'wp-admin/includes/translation-install.php';


$locale = get_locale();


if ( 'en_US' === $locale ) {

  $arr = array(

    'en' => array (

      'iso' => 'en',
      'language' => 'en_US',
      'english_name' => "English",
      'native_name'  => "English",
      'flag' => 'us.png',

    ),

  );

} else {

  
  $translations = wp_get_available_translations();

  $arr = array(

    $translations[ $locale ] [ 'iso' ] [ 1 ] => array (

      'iso' => $translations[ $locale ] [ 'iso' ] [ 1 ],
      'language' => $locale ,
      'english_name' => $translations[ $locale ] [ 'english_name' ],
      'native_name'  => $translations[ $locale ] [ 'native_name' ],
      'flag' =>  $translations[ $locale ] [ 'iso' ] [ 1 ] . '.png',

    ),

  );


}

update_option( 'alwpr_langs', $arr );




