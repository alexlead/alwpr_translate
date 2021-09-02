<?php


/**
 * Plugin Name: WP TRANSLATE
 * @function
 *  
 **/

namespace ALPWRT;

  // Exit if accessed directly
  if ( ! defined( 'ABSPATH' ) ) {	
	exit;
}

class ALWPRT_SETTINGS {

  private $pages;
  private $slug = 'alwprie_';

  
  public function __construct()
  {
  
      $this->pages = array(
          array(
              'key' => 'languages',
              'label' =>__('WPR TRANSLATE', 'alwpr-translate')
          ),
        array(
              'key' => 'terms',
              'label' =>__('Translate Categories', 'alwpr-translate')
          ),
          array(
              'key' => 'widgets',
              'label' =>__('Translate Widget Headers', 'alwpr-translate')
          ),
          


          array(
            'key' => 'translate_system',
            'label' =>__('TRANSLATE System', 'alwpr-translate')
        ),
          array(
            'key' => 'languages',
            'label' =>__('TRANSLATE Settings', 'alwpr-translate')
        ),
          

      );
      
      // action added menu items - call class function menu config
      add_action('admin_menu', array(&$this, 'menu_config' ) );


  }

  /**
   * Add menu items to Admin dashboard
   * 
   * */  
  public function menu_config()
  {

      add_menu_page( 
          $this->pages[0]['label'], 
          $this->pages[0]['label'],
          'manage_options', 
          $this->slug . 'menu_' . $this->pages[0]['key'], 
          array(&$this , $this->pages[0]['key']), 
          'dashicons-translation', 
          10
      );

      foreach($this->pages as $key=>$value)
      {
          if ($key != 0){
              add_submenu_page( 
                  $this->slug . 'menu_' . $this->pages[0]['key'],
                  $value['label'],
                  $value['label'],
                  'manage_options', 
                  $this->slug . 'menu_' . $value['key'], 
                  array(&$this , $value['key']),  
                  10
              );

          }
      }
     
   
      
  }

/****
 * Edit categories translations
 * */ 
  public function terms(){

    require_once (ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-terms.php');

    $terms = new ALWPRT_TERMS();

    
    if (  isset( $_GET ['wpr_edit_term_id' ] ) && $_GET ['wpr_edit_term_id' ] > 0  ) {

        $terms->term_edit_metabox();

    } else {

        $terms->terms_list_metabox();

    }



    $status = '';

    $this->open_page( __FUNCTION__ , $status );

  }



/****
 * Edit Widget headers translations
 * */ 
public function widgets(){

    require_once (ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-widget-headers.php');

    $widgets = new ALWPRT_WIDGETS();

    
    if (  isset( $_GET ['wpr_edit_widget_id' ] ) && $_GET ['wpr_edit_widget_id' ] > 0  ) {

        $widgets->widget_edit_metabox();

    } else {

        $widgets->widget_list_metabox();

    }



    $status = '';

    $this->open_page(  __FUNCTION__  , $status );

  }



  /***
   * 
   * Open language list
   * 
   */ 
  public function languages(){
      
      $status = $this->get_langs();

      $this->open_page( __FUNCTION__ , $status );

  }

  public function translate_system(){
      

    require_once (ALWPR_TRANSLATE_DIR . '/admin/core/class-alwprt-translate-system.php');

    $systems = new ALWPRT_TRANSLATE_SYSTEM();


    if( isset ( $_POST['fsdlgiosjgfdfvcxzx'] ) && wp_verify_nonce( $_POST['fsdlgiosjgfdfvcxzx'] , 'wpr_select_translate_systems' ) ) {

        $systems->save_translate_system();

    }


    $systems->register_meta_box();

    $status = '';

      $this->open_page( __FUNCTION__ , $status );

  }

/***
* Open Page 
* 
* */ 
  public function open_page($page, $arr){



      $status = $arr;
      
      require( ALWPR_TRANSLATE_DIR . '/admin/templates/' . $page . '.php' );


  }





/***
 * Function return list of languages
 * @return array
 * */ 

  public function get_langs () {


   

    return get_option( 'alwpr_langs', array() );

  }

  /***
  * Function update list of languages
  * @return array
  * */ 

 public function add_langs_list ( $lang_id ) {

    $arr = $this->get_langs ();


    foreach ( $arr as $value ) {

        if ( $lang_id == $value[ 'language'] ) {
    
            return;
    
        }

    }


    require_once ABSPATH . 'wp-admin/includes/translation-install.php';

    $translations = wp_get_available_translations();

    $arr [ $translations[ $lang_id ] [ 'iso' ] [ 1 ] ] = array (

        'iso' => $translations[ $lang_id ] [ 'iso' ] [ 1 ],
        'language' => $lang_id ,
        'english_name' => $translations[ $lang_id ] [ 'english_name' ],
        'native_name'  => $translations[ $lang_id ] [ 'native_name' ],
        'flag' =>  $translations[ $lang_id ] [ 'iso' ] [ 1 ] . '.png',
  
    );


  update_option ( 'alwpr_langs', $arr );

 }


  /***
  * Function update list of languages
  * @return array
  * */ 

 public function remove_langs_list ( $lang_id ) {

  $arr = $this->get_langs ();

  unset ( $arr[ $lang_id ] );

  update_option ( 'alwpr_langs', $arr );

}



}