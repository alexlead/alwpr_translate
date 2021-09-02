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
<div class="table wpr_table">

    <?php
foreach ( $arr as $key=>$value ) {
  ?>

    <div class="row wpr_row">
        <div class="cell wpr_cell">

            <?php echo $key; ?>

        </div>

        <div class="cell wpr_cell">

            <img src="<?php echo ALWPR_TRANSLATE_URL . '/flags/' . $value['flag']  ; ?>">

        </div>
        <div class="cell wpr_cell">

            <?php echo  $value['english_name']  ; ?>

        </div>
        <div class="cell wpr_cell">

            <?php echo  $value['native_name']  ; ?>

        </div>
        <div class="cell wpr_cell">

            <?php
if ( $value ['language'] != get_locale() ) {
?>

            <a
                href="?page=<?php echo $_GET['page'] ; ?>&wprt_lang_remove=delete&wprt_lang_locale=<?php echo $key; ?>"><?php _e( 'Delete', 'alwpr-translate' ); ?></a>

            <?php
}
?>



        </div>

    </div>

    <?php
}
?>
</div>