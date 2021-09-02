<?php


/**
 * Plugin Name: WP TRANSLATE
 * @function
 *  
 **/

/**
 * Declaire common functions from here
 * */ 


require_once ( ALWPR_TRANSLATE_DIR . '/core/interfaces/interface-alwprt-translate-system.php' );
require_once ( ALWPR_TRANSLATE_DIR . '/core/translate_systems/class-alwprt-translate-base.php' );


// Registered translations systems
// Should have method in class ALWPRT_TRANSLATE_CONTENT
// class file in /core/translate_systems base on interface ALWPRT_TRANSLATE_SYSTEM
    global $translate_systems ;

    $translate_systems = array (

    'libretranslate', 'google', 'whatsmate',
  
  );

  


/**
 * Get current settings of translations 
 * */
if ( !function_exists( 'alwprt_get_current_translation_system' ) ) { 

    function alwprt_get_current_translation_system(){
 
        // available system
        global $translate_systems;


        $translate_system = get_option ( 'alwpr_translate_system', array() );  
        
            if ( !in_array( $translate_system['name'],  $translate_systems) || !isset ( $translate_system['name'] ) ) {

                $translate_system['name'] = $translate_systems[0];
                $translate_system['key_api'] = '';
            
            }

            if ( !file_exists( ALWPR_TRANSLATE_DIR . '/core/translate_systems/class-alwprt-translate-with-' . $translate_system['name'] . '.php' ) ) {

                $translate_system['name'] = $translate_systems[0];
                $translate_system['key_api'] = '';
            }

            if ( $translate_system['name'] == 'google' && $translate_system['key_api'] == '' ) {

                $translate_system['name'] = $translate_systems[0];
                $translate_system['key_api'] = '';

            }

            return $translate_system;
    }  

}

/**
 * Get Current Translations system 
 * */ 

 if ( !function_exists ( 'alwprt_set_translate_system' ) ) {

    function alwprt_set_translate_system () { 
        
        $translate_system = alwprt_get_current_translation_system();

        require_once ( ALWPR_TRANSLATE_DIR . '/core/translate_systems/class-alwprt-translate-with-' . $translate_system['name'] . '.php' );

        $translate_system[ 'class_name' ] = 'ALWPRT_TRANSLATE_WITH_'. strtoupper( $translate_system['name'] ) ;

        return  $translate_system;

    }

 }

 $translate_system = alwprt_set_translate_system ();

/**
 * Translate Function For All Places
 * 
 * */  

$alwprt_translate = new $translate_system[ 'class_name' ] ( $translate_system['key_api'] );