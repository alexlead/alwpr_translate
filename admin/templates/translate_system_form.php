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


$current_system = get_option ( 'alwpr_translate_system', array() );



?>


<form id="wpr_translate_systems" class="wpr-form" method="POST">

      <?php 

      wp_nonce_field('wpr_select_translate_systems', 'fsdlgiosjgfdfvcxzx'); 

      ?>


<?php



foreach ( $status as $value ){

  
  echo '<label><input type="radio" name="wpr_translate_system" value="' . $value . '"';
  
  if ( $value == $current_system ['name'] )  {

    echo 'checked="checked"';

  }
  
  echo '>' . $value . ' </label><br>';


}



?>

<div class='wpr-form'>
    <label for="wpr_key_api"> Key API </label>
    <input type="text" name="wpr_key_api" id="wpr-key-api" value="<?php echo $current_system ['key_api'] ;?>" >
</div>


<input type="submit" value="<?php esc_attr_e( 'Save', 'alwpro-woo-orders'); ?>">

</form>
