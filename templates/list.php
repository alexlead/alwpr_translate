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

if ( is_user_logged_in () ) {

  $current_lang = get_user_option ( 'alwpr-lang', $current_user->ID);

} else {

  $current_lang = $_COOKIE [ 'wpr-lang' ] ;

}

?>


<ul class="wpr-lang-list wpr-lang-list-flags">
<?php 
foreach ( $available_lang as $key=>$value ) {

  ?>
<li class="wpr-lang-list-element wpr-lang-flag-element">
    <a href="?wpr-lang=<?php echo $key; ?>"
    
    <?php 
    
    if  ( $current_lang  == $key ) {

      echo 'class="selected"';

    } 

    ?>

    >
        <img src="<?php echo ALWPR_TRANSLATE_URL . '/flags/' . $value['flag'] ; ?>" alt="<?php echo $key; ?>">
    </a>
</li>

<?php
}

?>

</ul>

