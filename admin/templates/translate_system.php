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

<h1>
<?php _e('Translation systems', 'alwpr-translate'); ?>
</h1>

<div class="edit-post-layout__metaboxes">
  <div class="edit-post-meta-boxes-area is-normal">
    <div id="postbox-container-1" class="postbox-container ">
      
      <?php
    
        do_meta_boxes( 'wpr-translate-system', 'normal', null );

      ?>

    </div>
  </div>
</div>
