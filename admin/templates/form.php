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





?>


<form id="wpr_lang" class="wpr-form" method="POST">

      <?php 

      wp_nonce_field('wpr_add_language', 'ahsdkfad'); 

      ?>


<label for="wpr-lang"><?php _e('Select language', 'alwpr-translate'); ?></label>

<?php

$translation = wp_get_available_translations();

echo '<select id="lang" name="wpr-lang" class="wpr-form-select">';

foreach ( $translation as $key=>$value ){


  echo '<option value="' . $key . '">' . $value['english_name'] . '/' . $value['native_name']. '</option>';


}


echo '</select>';

?>


<input type="submit" value="<?php esc_attr_e( 'Submit', 'alwpro-woo-orders'); ?>">

</form>
