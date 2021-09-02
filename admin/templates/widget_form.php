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
			wp_nonce_field('alwprt_widget_editor', 'sgdfgtyere'); 
		?>
    <input type="hidden" name="widget_id" id="wpr-widget-id" value="<?php echo $widget_id ;?>" >
    <input type="hidden" name="widget_block" id="wpr-widget-block" value="<?php echo $widget_block ;?>" >
    <br>
<div class='wpr-form'>
    <label for="widget_name"> Widget Title </label>
    <input type="text" name="widget_name" id="wpr-widget-name" value="<?php echo $widget_name ;?>" disabled="disabled">
</div>
<?php 

foreach ( $langs as $lang=>$value ) {

?>
<div class='wpr-form'>
    <label for="translate-<?php echo $lang; ?>"> Translate <?php echo $value; ?> </label>
    <input type="text" name="translate[<?php echo $lang; ?>]" id="translate-<?php echo $lang; ?>" value="<?php echo $widget_translate[ 'translate-' . $lang ]['title']; ?>">
</div>

<?php

}

?>

<br>
    <input type="submit" id="alwpr_widgets_translate" value="<?php esc_attr_e('Save', 'alwpr-translate'); ?>">

</form>