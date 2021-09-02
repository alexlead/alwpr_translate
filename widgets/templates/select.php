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


wp_enqueue_style( 'alwprt_translate_menu_style',  ALWPR_TRANSLATE_URL . '/assets/css/style.css');

$available_lang = get_option ( 'alwpr_langs', array() );

global $current_user;



  if  ( isset ($_GET [ 'lang' ])  ) {
    
    $current_lang = $_GET [ 'lang' ] ;
    
  }



?>

<form method="GET">


<select class="wpr-lang-list wpr-lang-list-flags" name="lang" onchange="this.form.submit()">
    <?php 
foreach ( $available_lang as $key=>$value ) {

  ?>
    <option value="<?php echo $key;?>" <?php
if  ( $current_lang  == $key ) {

echo 'selected="selected"';

} ?>>

        <?php echo $key ;?>

    </option>

    <?php
}

?>

</select>

</form>