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

<form method='post'> 


    <?php 
			// hidden fields;
			// hdden verification for post - random field  
			wp_nonce_field('alwprt_term_editor', 'sfkldjfserti'); 
		?>
    <input type="hidden" name="term_id" id="wpr-term" value="<?php echo $term_id ;?>" >
    <br>
<div class='wpr-form'>
    <label for="term_name"> Term </label>
    <input type="text" name="term_name" id="wpr-term-name" value="<?php echo $term_name ;?>" disabled="disabled">
</div>
<?php 

foreach ( $langs as $lang=>$value ) {

?>
<div class='wpr-form'>
    <label for="translate-<?php echo $lang; ?>"> Translate <?php echo $value; ?> </label>
    <input type="text" name="translate[<?php echo $lang; ?>]" id="translate-<?php echo $lang; ?>" value="<?php echo $term_meta[ 'translate-' . $lang ]; ?>">
</div>

<?php

}

?>

<br>
    <input type="submit" id="alwpr_terms_translate" value="<?php esc_attr_e('Save', 'alwpr-translate'); ?>">

</form>