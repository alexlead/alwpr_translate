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

<table class="wp-list-table widefat fixed striped table-view-list pages">
	<thead>
	<tr>
		<td id="cb" class="manage-column column-cb check-column"></td>
    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
   <span> <?php _e( 'Title', 'alwprt-translate' );?> </span>
   </th>
   <th scope="col" id="translate" class="manage-column column-translate"><?php _e( 'Translate', 'alwprt-translate' );?></th>
   </tr>
	</thead>

	<tbody id="the-list">
	
  
  <?php 
  
  foreach ( $widgets as $block=>$widget_block ) {
  foreach ( $widget_block as $key=>$widget ) {

    if ( strlen($widget['title']) < 1) {

      continue;

    }

  ?>
  <tr class="iedit author-self level-0  type-page status-publish">
			<th scope="row" class="check-column">	
			</th>


  <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">

  <a href="<?php echo add_query_arg( array('wpr_edit_widget_id'=>$key, 'widget_block' => $block, )  , $_SERVER['REQUEST_URI']  ) ;?>">
      
      <?php echo $widget['title']; ?>
    
    </a>
	</td>
  
  <td class="translate column-translate" data-colname="Translate">
  
  <a href="<?php echo add_query_arg(  array('wpr_edit_widget_id'=>$key, 'widget_block' => $block, )   , $_SERVER['REQUEST_URI'] ) ;?>">

  <?php _e( 'Check translation', 'alwprt-translate' );?>
</a>

  </td>
    
    
    </tr>

<?php
  }

}
?>

	</tbody>

	<tfoot>
	<tr>
		<td id="cb" class="manage-column column-cb check-column"></td>
    <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
   <span> <?php _e( 'Title', 'alwprt-translate' );?> </span>
   </th>
   <th scope="col" id="translate" class="manage-column column-translate"><?php _e( 'Translate', 'alwprt-translate' );?></th>
   </tr>
   	</tfoot>

</table>