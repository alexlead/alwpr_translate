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
  
  foreach ( $terms as $term ) {


  ?>
  <tr class="iedit author-self level-0  type-page status-publish">
			<th scope="row" class="check-column">	
			</th>


  <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">

  <a href="<?php echo add_query_arg( 'wpr_edit_term_id' , $term->term_id  , $_SERVER['REQUEST_URI']  ) ;?>">
      
      <?php echo $term->name; ?>
    
    </a>
	</td>
  
  <td class="translate column-translate" data-colname="Translate">
  
  <a href="<?php echo add_query_arg( 'wpr_edit_term_id' , $term->term_id  , $_SERVER['REQUEST_URI'] ) ;?>">

  <?php _e( 'Check translation', 'alwprt-translate' );?>
</a>

  </td>
    
    
    </tr>

<?php
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