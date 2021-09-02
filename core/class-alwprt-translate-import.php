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

class ALWPRT_TRANSLATE_IMPORT
{

  private $csv_user;

  private $post_type;

  public function __construct() {

    $this->csv_user = array(

            'ID'                    =>     'id',
            'post_title'            =>     'title',             
            'post_excerpt'          =>     'short_description',
            'post_content'          =>     'full_description',
            'acf_attributes'        =>     'product_attributes',   // ACF Fields

    );


}

    // check csv headers for correct data
    public function check_csv($arr) {

      $keys = array();

          foreach ( $arr as $key=>$value ) {

              $str = array_search( $value, $this->csv_user );

              if ( $str ) {

                  $keys[ $key ] = $str;

              }

          }


          return $keys;
  }


  // get ID of translation if it exists
  public function get_translation_id( $post_parent, $post_type ) {

    global $wpdb;

    $sql = 'SELECT ID FROM ' . $wpdb->posts . ' WHERE 1=1 '; 
    
    $sql .= ' AND post_parent = ' .$post_parent ;

    $sql .= ' AND post_type = "' .$post_type .'"';

    $request = $wpdb->get_row($sql);

    return $request->ID ;

  }

      // ------------------ update product ----------------------- 
      public function update_post_translation( $arr ) {

        

        global $current_user;

        if ( get_post_type( $arr ['ID'] ) != 'product' ) {

            return;

        }

        $check_update_post = get_post( $arr ['ID'] );



        if ( $check_update_post->post_author != $current_user->id ) {

            return;

        }

        $post_data [ 'post_type' ] = $this->post_type;

        $post_data [ 'post_parent' ] = $arr ['ID'];

        $post_data [ 'post_title' ] = sanitize_post ( $arr ['post_title'] );

        $post_data [ 'post_excerpt' ] = sanitize_post ( $arr ['post_excerpt'] );

        $post_data [ 'post_content' ] = sanitize_post ( $arr ['post_content'] );

        $post_data [ 'post_status' ] = 'private' ;

        $post_data ['post_modified']   =  date('Y-m-d H:i:s');

        $ID = $this->get_translation_id( $post_data [ 'post_parent' ], $post_data [ 'post_type' ] );
        
        if ( $ID > 0 ) {
          
          $post_data [ 'ID' ] = $ID;

          var_dump ($post_data);
                    
          wp_update_post(  $post_data  );

        } else {

          wp_insert_post( wp_slash( $post_data ) );

        }


    }

    // update DB from CSV
    public function save_data_by_product_to_db( $arr ) {

      foreach ( $arr as $value ) {

              $this->update_post_translation($value);

      }

  }



// getting file csv
public function getting_file(){

  global $alwpr_notification;



  if (!$_FILES || $_FILES["alwprt_upload_translate_file"]["error"] != UPLOAD_ERR_OK) {

      $alwpr_notification = esc_attr__('Problem with loading file!', 'alwpr-translate');

      return;

  }

  $path = wp_upload_dir()[ 'basedir' ]."/alwprie_tmp/";

  if ( !is_dir( $path ) ) {

      mkdir ( $path, 0777, true ) ;

  }


  $name = date("Y-m-d_H-i-s") . '-tmp.csv';

  move_uploaded_file( $_FILES [ "alwprt_upload_translate_file" ] [ "tmp_name" ],  $path . $name);

  $i = 0;
  
  if ( ($handle = fopen($path . $name, "r") ) !== FALSE )
  {
      while ( ($line = fgetcsv( $handle, 0, ';') ) !== FALSE)
      {

         if ( $i == 0 ) {
              
              $keys = $this->check_csv( $line ) ;
              

              if ( count( $keys ) != count( $this->csv_user ) ) {

                  $alwpr_notification = esc_attr__('Uploaded wrong file!', 'alwpr-translate');
                  
                  break;
                  
              }
              
              $i++;
              continue;
          }
          
          foreach ( $keys as $key=>$value ) {
              
              $lines[ $i ][ $value ] =  $line[ $key ];
              
          }
          
          $i++;

          // for big file we set limit in 500 strings
          if ($i > 500 ) {

              break;
          }
      }
      
      fclose($handle);
  } else {
      
      $alwpr_notification = esc_attr__('Problem with loading file!', 'alwpr-translate');
      
  }
  
  // set post type

  if ( isset( $_POST[ 'alwpr_translate_import' ] ) && strlen( $_POST[ 'alwpr_translate_import' ] ) < 5 )  {

    $this->post_type = 'translate-' . sanitize_text_field ( $_POST[ 'alwpr_translate_import' ] );

  }


  if ( count( $lines ) > 0 ) {

      // prepare ACF fields array
//      $this->acf_array_prepare();
      
      $this->save_data_by_product_to_db( $lines );
      
      $alwpr_notification = esc_attr__('File loaded successful!', 'alwpr-translate');


 }

// remove file
  unlink( $path . $name ); 


}


}