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



require_once ( ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-settings.php' );

$get_setting_page = new ALPWRT\ALWPRT_SETTINGS();


wp_enqueue_style('alwprt-admin-translate-style', ALWPR_TRANSLATE_URL . '/admin/assets/css/admin_style.css') ;

if ( !function_exists ( 'alwprt_update_options' ) ) {
  
  function alwprt_update_options () {
    
    global   $get_setting_page;
    
    if ( isset ( $_GET[ 'wprt_lang_remove' ] ) &&  $_GET[ 'wprt_lang_remove' ] == 'delete' ){
      
      if ( isset ( $_GET[ 'wprt_lang_locale' ] ) &&  strlen ($_GET[ 'wprt_lang_locale' ] ) < 6 ) {
        
        $get_setting_page->remove_langs_list( sanitize_text_field ($_GET[ 'wprt_lang_locale' ] ) );        
        
        header( "Location: ?page=" . $_GET['page'] ); 
        
      }
      
    }
    
    
    
    if ( isset( $_POST[ 'sgdfgtyere' ] ) && wp_verify_nonce( $_POST[ 'sgdfgtyere' ], 'alwprt_widget_editor' )) {


      require_once (ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-widget-headers.php');

      $terms = new ALPWRT\ALWPRT_WIDGETS();

      $terms->widget_save_meta();


    }

    if ( isset( $_POST[ 'sfkldjfserti' ] ) && wp_verify_nonce( $_POST[ 'sfkldjfserti' ], 'alwprt_term_editor' )) {


      require_once (ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-terms.php');

      $terms = new ALPWRT\ALWPRT_TERMS();

      $terms->term_save_meta();


    }

    if ( isset( $_POST[ 'ahsdkfad' ] ) && wp_verify_nonce( $_POST[ 'ahsdkfad' ], 'wpr_add_language' )) {

      
      if ( isset ( $_POST[ 'wpr-lang' ] ) && strlen ( $_POST[ 'wpr-lang' ] ) < 6  ) {
        
        
        $get_setting_page->add_langs_list( sanitize_text_field ( $_POST[ 'wpr-lang' ] ) ); 
        
        
      }
      
    }

    // Generate translations for statuses 
    if ( isset ( $_GET [ 'status_translations' ] ) && $_GET [ 'status_translations' ] == 'required' ) {

      if ( isset ( $_GET [ 'wpr_status_id' ] ) && sanitize_key ( $_GET [ 'wpr_status_id' ] ) > 0 ) {

        $post_type = get_post_type( $_GET [ 'wpr_status_id' ] );
  
        if ( $post_type == 'wpr-order-status' || $post_type == 'wpr-status-group' ) {
    

          require_once ( ALWPR_TRANSLATE_DIR . '/core/class-alwprt-translate-content.php' );
      
          $get_translation = new ALWPRT_TRANSLATE_CONTENT( 'en');
          
          $translate = $get_translation->iteration_status_translation ( sanitize_key ( $_GET [ 'wpr_status_id' ] ) );

        }

      }

    }
    
    
  }
  
}

add_action ( 'plugins_loaded' , 'alwprt_update_options' );


if ( !function_exists ('alwprt_languages_list') ) {
  
  function alwprt_languages_list( ){
    
    global   $get_setting_page;
    
    $arr = $get_setting_page->get_langs();
    
    require( ALWPR_TRANSLATE_DIR . '/admin/templates/list.php' );
    
  }
  
}


if ( !function_exists ('alwprt_languages_add_form') ) {
  
  function alwprt_languages_add_form( ){
    
    
    require( ALWPR_TRANSLATE_DIR . '/admin/templates/form.php' );
    
  }
  
}




if ( !function_exists ( 'alwprt_register_meta_boxes' ) ) {
  
  function alwprt_register_meta_boxes () {
    
    
    require_once ABSPATH . 'wp-admin/includes/translation-install.php';
    
    add_meta_box( 'lang_list', __('Available languages list', 'alwpr-translate'), 'alwprt_languages_list', 'language_list', 'normal' );
    add_meta_box( 'lang_form', __('Add language', 'alwpr-translate'), 'alwprt_languages_add_form', 'language_list', 'normal' );
    
  }
  
}


add_action ( 'alwprt_meta_boxes_load', 'alwprt_register_meta_boxes' );


require_once (ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-admin-meta-boxes.php') ;
require_once (ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-admin-post-edit.php') ;

//    $test = new WPM_Admin_Posts();

    



// Add columns to products, posts, pages lists
if  ( !function_exists( 'alwprt_language_columns' ) ) {
  
  function alwprt_language_columns( $columns ) {
    
    if ( empty( $columns ) && ! is_array( $columns ) ) {
      $columns = array();
    }
    
    if ( isset( $columns['translate'] ) ) {
      return $columns;
    }
    
    
    $language = array( 'translate' => __( 'Translate', 'alwpr-translateg' ), 
                      'generate' => __( 'Generate', 'alwpr-translateg' ), );
    
    
    
    if ( isset( $columns['title'] ) ) {
      
      foreach ( $columns as $key=>$value ) {
        
        $res [ $key ] = $value; 
        
        if ( $key == 'title' ) {
          
                  $res [ 'translate' ] = $language [ 'translate' ] ;

                  $res [ 'generate' ] = $language [ 'generate' ] ;
                  
              }
              
            }
            
            return $res;
            
          } 
          
          if ( isset( $columns['name'] ) ) {
            
            foreach ( $columns as $key=>$value ) {
              
              $res [ $key ] = $value; 
              
              if ( $key == 'name' ) {
                
                $res [ 'translate' ] = $language [ 'translate' ] ;
                
                $res [ 'generate' ] = $language [ 'generate' ] ;
              }
              
            }
            
            return $res;
            
          } 
          
          

          $columns = array_merge( $columns, $language );
          
          return $columns;
          
        }
        
      }
      
      add_filter( "manage_posts_columns", 'alwprt_language_columns' );
      
      add_filter( "manage_pages_columns", 'alwprt_language_columns' );
      
/**
 * Making dublicate of Page or Post in Admin Dashboard
 * Edit to translation
 * */ 
if ( !function_exists ( 'alwprt_dublicate_post_for_translation' ) ) {

    function alwprt_dublicate_post_for_translation() {

      if ( isset ($_GET[ 'wpr_translation' ]) &&  $_GET[ 'wpr_translation' ] == 'generate' ) {
  
     if ( isset ($_GET[ 'wpr_translate_lang' ]) &&  strlen ( $_GET[ 'wpr_translate_lang' ] ) < 5 ) {
     
       if ( isset ($_GET[ 'post_parent' ]) &&   $_GET[ 'post_parent' ] > 0  ) {
          
            require_once ( ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-post-translate-dublicate.php' );
      
            $alwprt_dublicate = new ALWPRT_POST_TRANSLATE_DUBLICATE();
            
            $generated_id = $alwprt_dublicate->dublicate_post_for_translation( sanitize_key($_GET[ 'post_parent' ]), sanitize_text_field($_GET[ 'wpr_translate_lang' ] ) );


            wp_redirect( add_query_arg ( array('action'=>'edit', 'post'=> $generated_id), admin_url().'post.php' ) );
          }


        }

      }

      return;
    }


}

add_action ('plugins_loaded', 'alwprt_dublicate_post_for_translation');


      // 
      
      require_once ( ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-post-links.php' );
      
      $alwprt_translate_links = new ALWPRT_POST_LINKS();
      
      // Fill column with links of translations

      if ( !function_exists ( 'alwprt_fill_translate_links' ) ) {
        
  function alwprt_fill_translate_links ( $colname, $post_id ){

    
    global  $alwprt_translate_links;


    //  Translated pages links
    if( $colname === 'translate' ){
      
      foreach (  $alwprt_translate_links->langs as $key=>$value ) {

        $get_translate_id = $alwprt_translate_links->get_db_translation_post( $post_id , $key);

        if ( $get_translate_id > 0 ) {

          echo ' <a href="'. admin_url() .'post.php?action=edit&post='. $get_translate_id .'">' ; 

          echo '<img src="' .ALWPR_TRANSLATE_URL . '/flags/' . $value['flag'] . '" alt="' . $key . '">';
          
          echo '</a> ';

        } 

      }

     
    }

    // links for making duble page for translation 
    if ( $colname === 'generate' ) {
      
      foreach (  $alwprt_translate_links->langs as $key=>$value ) {

        $get_translate_id = $alwprt_translate_links->get_db_translation_post( $post_id , $key);

        if ( $get_translate_id < 1 ) {

          echo ' <a href="'. add_query_arg ( array ('wpr_translation'=>'generate', 'wpr_translate_lang' => $key, 'post_parent'=> $post_id , ) , $_SERVER['REQUEST_URI'] ). '">' ; 

          echo '<img src="' .ALWPR_TRANSLATE_URL . '/flags/' . $value['flag'] . '" alt="' . $key . '">';
          
          echo '</a> ';

        } 

      }

    }
  
  
    
  }
  
}

add_action('manage_'.'post'.'_posts_custom_column', 'alwprt_fill_translate_links', 5, 2 );

add_action('manage_'.'page'.'_posts_custom_column', 'alwprt_fill_translate_links', 5, 2 );

add_action('manage_'.'product'.'_posts_custom_column', 'alwprt_fill_translate_links', 5, 2 );



// add language links to post
// the links on metabox area
if ( !function_exists ( 'alwprt_translate_buttons' ) ) {


function alwprt_translate_buttons () {
  

  $parent_post = sanitize_key ( $_GET [ 'post' ] );

  global  $alwprt_translate_links;

    
  $post_type = get_post_type( $parent_post );

  if ( $post_type == 'post' || $post_type == 'page'  || $post_type == 'product' ) {
// translated page links
    echo '<div class="misc-pub-section wpr-edit-translation">';
    echo '<p>' . __('Edit translation', '') . '</p>';
    foreach (  $alwprt_translate_links->langs as $key=>$value ) {
      
      $get_translate_id = $alwprt_translate_links->get_db_translation_post(  $parent_post , $key);
      
      if ( $get_translate_id > 0 ) {
        
        echo ' <a href="'. admin_url() .'post.php?action=edit&post='. $get_translate_id .'">' ; 
        
        echo '<img src="' .ALWPR_TRANSLATE_URL . '/flags/' . $value['flag'] . '" alt="' . $key . '">';
        
        echo '</a> ';
        
      }
      
    }
    
    echo '</div>';

// dublicate page for translation
    echo '<div class="misc-pub-section wpr-generate-translation">';
    echo '<p>' . __('Generate translation', '') . '</p>';
    foreach (  $alwprt_translate_links->langs as $key=>$value ) {
      
      $get_translate_id = $alwprt_translate_links->get_db_translation_post(  $parent_post , $key);
      
      if ( $get_translate_id < 1 ) {
        
        echo ' <a href="'. add_query_arg ( array ('wpr_translation'=>'generate', 'wpr_translate_lang' => $key, 'post_parent'=> $parent_post , ) , $_SERVER['REQUEST_URI'] ). '">' ; 
        
        echo '<img src="' .ALWPR_TRANSLATE_URL . '/flags/' . $value['flag'] . '" alt="' . $key . '">';

        echo '</a> ';
        
      }
      
    }
    
    echo '</div>';

  }
  
}

}

add_action ('post_submitbox_minor_actions' , 'alwprt_translate_buttons', 10, 1);




// add language links to status
if ( !function_exists ( 'alwprt_status_translate_buttons' ) ) {


  function alwprt_status_translate_buttons () {
    
  
    $parent_post = sanitize_key ( $_GET [ 'wpr_status_id' ] );
  
    global  $alwprt_translate_links;
  
      
    $post_type = get_post_type( $parent_post );
  
    if ( $post_type == 'wpr-order-status' || $post_type == 'wpr-status-group' ) {

      $need_traslations = false;
  
      echo '<div class="misc-pub-section">';
      
      foreach (  $alwprt_translate_links->langs as $key=>$value ) {
        
        $get_translate_id = $alwprt_translate_links->get_db_translation_post(  $parent_post , $key);
        
        if ( $get_translate_id > 0 ) {
          
          echo ' <a href="'. admin_url() .'post.php?action=edit&post='. $get_translate_id .'">' ; 
          
          echo '<img src="' .ALWPR_TRANSLATE_URL . '/flags/' . $value['flag'] . '" alt="' . $key . '">';
          
          echo '</a> ';
          
        } else {

          $need_traslations = true;

        }
        
      }
      
      echo '</div>';

      if ( $need_traslations  ) {

        $generate_translations = add_query_arg ( array( 'status_translations' => 'required' ), $_SERVER [ 'REQUEST_URI' ] );

        echo ' <a href="'. $generate_translations .'">' ; 
          
        echo  __('Generate translations', 'alwpr-translate');
        
        echo '</a> ';

      }
  
    }
    
  }
  
  }


add_action ('admin_before_status_form' , 'alwprt_status_translate_buttons', 10, 1);