<?php

/**
 * Plugin Name: WP TRANSLATE
 * Description: Plugin is for translation of WP articles
 * Plugin URI: https://github.com/alexlead/
 * Version: 1.0.1
 * Author: Alexander Lead
 * Author URI: https://codepen.io/alexlead/
 * Requires at least: 4.8
 * Tested up to: 5.7
 * Text Domain: alwpr-translate
 * Domain Path: /languages
 *  
 **/

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {	
	exit;
}


//define plugin paths
if ( ! defined( 'ALWPR_TRANSLATE_DIR' ) ) {

	define( 'ALWPR_TRANSLATE_DIR',  dirname( __FILE__ )  );

}

if ( ! defined( 'ALWPR_TRANSLATE_URL' ) ) {

  define( 'ALWPR_TRANSLATE_URL',  plugins_url( '', __FILE__ )  );

}



// activate widgets

if ( !function_exists ( 'alwprt_widgets' ) )  {

  function alwprt_widgets () {
    
    require_once( ALWPR_TRANSLATE_DIR . '/widgets/class-alwprt-lang-menu-widget.php' );

    register_widget( 'Alwpr_Translate_Menu_Widget' );

  }

}

add_action( 'widgets_init', 'alwprt_widgets' );



// init activation functions (add sql table for files)

if ( !function_exists( 'alwprt_plugin_activation_translation' ) ){

    function alwprt_plugin_activation_translation(){

        require_once( ALWPR_TRANSLATE_DIR . '/inc/init.php' );


    }


}

register_activation_hook( __FILE__, 'alwprt_plugin_activation_translation' );




require_once( ALWPR_TRANSLATE_DIR . '/inc/common.php' );


if ( is_admin()  ){

	require_once( ALWPR_TRANSLATE_DIR . '/admin/functions.php' );

} else {

	require_once( ALWPR_TRANSLATE_DIR . '/inc/functions.php' );

}


// register translations post types

if ( !function_exists ( 'alwprt_register_post_types' ) ) {

    function alwprt_register_post_types () {

      
      $site_lang = get_locale();
      $available_lang = get_option ( 'alwpr_langs', array() );

      // register post translates
      foreach ( $available_lang as $key=>$value ) {
        
        // site base language
        if ( $value['language'] ==  $site_lang ) {
          
          continue;
        }

        $args = array(
  
          'label' => 'Translate '.$value['english_name'] . '/' . $value['native_name'],
          'description' =>  'Translations to other languages.',

          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          'show_in_menu'       => 'alwprie_menu_languages',
          'query_var'          => true,
          'rewrite'            => true,
          'capability_type'    => 'post',
          'has_archive'        => true,
          'hierarchical'       => false,
          'menu_position' => 22,
          'supports' => array ( 'title', 'editor', 'excerpt', ),
          'delete_with_user' => true,
          

        );
          
          $post_type = 'translate-'.$key;
    
          register_post_type( $post_type, $args );
      }

      //register nav-menu-translate

      foreach ( $available_lang as $key=>$value ) {
        
        // site base language
        if ( $value['language'] ==  $site_lang ) {
          
          continue;
        }

        $args = array(
  
          'label' => 'MENU Translate '.$value['english_name'] . '/' . $value['native_name'],
          'description' =>  'Translations to other languagues.',

          'public'             => true,
          'publicly_queryable' => true,
          'show_ui'            => true,
          'show_in_menu'       => 'alwprie_menu_languages',
          'query_var'          => true,
          'rewrite'            => true,
          'capability_type'    => 'post',
          'has_archive'        => false,
          'hierarchical'       => false,
          'menu_position' => 22,
          'supports' => array ( 'title', ),
          'delete_with_user' => true,
          

        );
          
          $post_type = 'nav-translate-'.$key;
    
          register_post_type( $post_type, $args );
      }


  


      }

    }


add_action( 'init', 'alwprt_register_post_types' );
