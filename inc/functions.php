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
  
  
  $wpr_traslations = array(
    'prepared' => false,
    
    'translate' => false,
    
  );
  
  
  // save option of lang by user
  if ( !function_exists ( 'alwprt_translate_save_option' ) ) {
    
    function alwprt_translate_save_option () {
      
      global $current_user;


      
      require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-switcher.php' );

      $lang_param = new ALWPRT_TRANSLATE_SWITCHER();


      
    $current_lang = $lang_param->get_current_lang() ;



    require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-link.php' );

    $add_links = new ALWPRT_TRANSLATE_LINK( $current_lang , $lang_param->to_translate);

    if ( $add_links->to_translate) {

      global $wpr_traslations;

      $wpr_traslations [ 'lang' ] = $current_lang;

      $wpr_traslations [ 'translate' ] = true;


    }




  }

}

add_action( 'init', 'alwprt_translate_save_option' ,  1, 0 );





if ( !function_exists( 'alwprt_get_post_translation' ) ) {
  
  function alwprt_get_post_translation() {

    global $post;
    
    global $wpr_traslations;

    // $wpr_traslations [ 'post_parent' ] = get_the_ID();
    $wpr_traslations [ 'post_parent' ] = $post->ID;

    require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-content.php' );
    
    $get_translation = new ALWPRT_TRANSLATE_CONTENT( $wpr_traslations [ 'lang' ] );
    
    $translate = $get_translation->get_translation ( $wpr_traslations [ 'post_parent' ] );
    
    $wpr_traslations [ 'data' ] = $translate;
    
    $wpr_traslations [ 'prepared' ] = true;
    
  }
  
}

// change commodity short description
if ( !function_exists ( 'alwprt_woo_product_translate_excerpt' ) ) {
  
  function alwprt_woo_product_translate_excerpt ( $excerpt ) {
    

    if( !in_the_loop()  ) {

      return $excerpt;
    
    }


    global $wpr_traslations;

    if ( $wpr_traslations [ 'post_parent' ] != get_the_ID() ) {

      $wpr_traslations ['prepared'] = false;

    }

    
    if ( !$wpr_traslations ['prepared'] ) {

      alwprt_get_post_translation();
  
    }
    
    if ( $wpr_traslations ['prepared'] ) {
      
      $excerpt = $wpr_traslations [ 'data' ] [ 'post_excerpt' ] ;
    
    }

    return $excerpt;
  
  }

}
// change commodity short description
if ( !function_exists ( 'alwprt_woo_product_translate_title' ) ) {
  
  function alwprt_woo_product_translate_title ( $title ) {
    
    if ( is_page() ) {

      global $post;

      global $wpr_traslations;
  
      require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-content.php' );
  
      $get_translation = new ALWPRT_TRANSLATE_CONTENT( $wpr_traslations [ 'lang' ] );
      
      $translate_id = $get_translation->get_page_content_translation  ( $post->ID );
  
      if ( $translate_id [ 'ID' ] > 0 ) {
      
        return $translate_id [ 'post_title' ];
      }  else {

        return $title;
      
      }
    
    }


if( !in_the_loop()  ) {

  return $title;

}

    global $wpr_traslations;

    if ( $wpr_traslations [ 'post_parent' ] != get_the_ID() ) {

      $wpr_traslations ['prepared'] = false;

    }

    
    if ( !$wpr_traslations ['prepared'] ) {

      alwprt_get_post_translation();
  
    }
    
    if ( $wpr_traslations ['prepared'] ) {
      
      $title = $wpr_traslations [ 'data' ] [ 'post_title' ] ;
    
    }

    return $title;
  
  }

}

// change commodity full description
if ( !function_exists ( 'alwprt_woo_product_translate_content' ) ) {
  
  function alwprt_woo_product_translate_content ( $content ) {
    




    global $wpr_traslations;

    if ( $wpr_traslations [ 'post_parent' ] != get_the_ID() ) {

      $wpr_traslations ['prepared'] = false;

    }

    
    if ( !$wpr_traslations ['prepared'] ) {
      
      alwprt_get_post_translation();
      
    }
    
    if ( $wpr_traslations ['prepared'] ) {
      
      $content =  $wpr_traslations [ 'data' ] [ 'post_content' ] ;

    }

    return $content ;
  
  }

}




// change commodity short description
if ( !function_exists ( 'alwprt_woo_product_translate_table_excerpt' ) ) {
  
  function alwprt_woo_product_translate_table_excerpt ( $excerpt, $prod ) {
    
    if ( is_page() ) {

      global $post;

      global $wpr_traslations;
  
      require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-content.php' );
  
      $get_translation = new ALWPRT_TRANSLATE_CONTENT( $wpr_traslations [ 'lang' ] );
      
      $translate_id = $get_translation->get_page_content_translation  ( $post->ID );
  
      if ( $translate_id [ 'ID' ] > 0 ) {
      
        return $translate_id [ 'post_title' ];
      }  else {

        return $title;
      
      }
    
    }

    global $wpr_traslations;

    if ( $wpr_traslations [ 'post_parent' ] != get_the_ID() ) {

      $wpr_traslations ['prepared'] = false;

    }

    
    if ( !$wpr_traslations ['prepared'] ) {

      alwprt_get_post_translation();
  
    }
    
    if ( $wpr_traslations ['prepared'] ) {
      
      $excerpt = $wpr_traslations [ 'data' ] [ 'post_excerpt' ] ;
    
    }

    return $excerpt;
  
  }

}




/**
 * Translate products categories 
 * */ 
if ( !function_exists ( 'alwprt_cat_translate' ) ) {

function alwprt_cat_translate ($p1, $p2 ) {
  
  require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-content.php' );
  
  global $wpdb;
  
  global $wpr_traslations;
  
  foreach ($p1 as $key=>$value) {
    
    
    $get_translation = new ALWPRT_TRANSLATE_CONTENT( $wpr_traslations [ 'lang' ] );
    
    $translate = $get_translation->get_term_translation  ( $value->name );
    
    if ( strlen ( $translate ) > 0  ) {
      
      $value->name = $translate;
      
    }
    
  }
  
  return $p1;
}

}


/**
 * Tanslate widget titles
 * */ 
if ( ! function_exists ( 'alwprt_widget_title_translate' ) ) {

  function alwprt_widget_title_translate ( $title, $instance) {

    require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwrt-translate-widget.php' );

    global $wpr_traslations;
          
     $get_translation = new ALWPRT_TRANSLATE_WIDGET( $wpr_traslations [ 'lang' ] );
      
     $title = $get_translation->get_translation_by_title ( $title );    

    

    return $title;
  }

}



if ( !function_exists ( 'alwprt_category_dropdown' ) ) {

  function alwprt_category_dropdown ( $cat_name, $cats ) {


    $term = get_term_by('name', $cat_name, 'product_cat' );
    
    if ( $term->term_id < 1 ) {
      
      $term = get_term_by('name', $cat_name, 'category' );
      
        if ( $term->term_id < 1 ) {

          return $cat_name;

        }
    
    }

    global $wpr_traslations;

    $get_translation = new ALWPRT_TRANSLATE_CONTENT( $wpr_traslations [ 'lang' ] );
 
    $cat_name = $get_translation->get_term_translation  ( $cat_name );

    return $cat_name;

  }


}






/**
 * Init of all filters
 * */ 
if ( !function_exists ( 'alwprt_do_translation' ) ) {

    function alwprt_do_translation () {


      global $wpr_traslations;

      if ( !$wpr_traslations ['translate'] ) {


        return;

      }

      add_filter( 'woocommerce_product_title', 'alwprt_woo_product_translate_title', 5 );
      add_filter( 'post_title', 'alwprt_woo_product_translate_title', 5 );
      add_filter( 'the_title', 'alwprt_woo_product_translate_title', 5 );
      add_filter( 'wp_title', 'alwprt_woo_product_translate_title', 5 );
      add_filter( 'woocommerce_short_description', 'alwprt_woo_product_translate_excerpt', 5 );

      add_filter( 'the_content', 'alwprt_woo_product_translate_content', 25 );

      add_filter( 'wpr_status_title', 'alwprt_status_translate_title', 5, 2 );
      add_filter( 'wpr_status_content', 'alwprt_status_translate_content', 5, 2 );
      

      add_filter ( 'get_the_terms' , 'alwprt_cat_translate', 10, 2 ); // translate term

      add_filter( 'list_cats', 'alwprt_category_dropdown', 10, 2 ); // dropdown categories

      add_filter( 'widget_title', 'alwprt_widget_title_translate', 10, 2 );



    }

}


 add_action ( 'init' , 'alwprt_do_translation' , 30);



// add language attribute to menu 


/**
 * Translate menu items
 * 
*/

if ( !function_exists ( 'alwprt_menu_titles_translate' ) ) {

  
  
  function alwprt_menu_titles_translate( $title, $item, $args, $depth ){
    // parent_id: $item->ID
    
    global $wpr_traslations;
    
    
    if ( !$wpr_traslations [ 'translate' ] ) {
      

    return $title ;
    
  }
  
  $parent_post = $item->ID ;
  
  
  if ( $parent_post < 1 ) {
    
    return $title ;
    
  }
  
  require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-content.php' );
  
  $get_translation = new ALWPRT_TRANSLATE_CONTENT( $wpr_traslations [ 'lang' ] );
  
  $translate = $get_translation->get_menu_translation ( $parent_post, $title );
  
  
  
	return $translate ;
}

}

add_filter( 'nav_menu_item_title', 'alwprt_menu_titles_translate', 10, 4 );

