<?php
/**
 * 后台查看可用api Item页面
 *
 * @package    includes/pages/
 * @subpackage admin
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */
?>
<?php 
/**
 * business logic
 */
$api_group_selection_array = apify_get_api_group_selection(array('status'=>1, 'visiable'=>1), 'group_name');
$type_selection_array = array('-1'=> __('-- Type --'), '1'=>__('Normal'), '2'=>__('security'));
$visiable_selection_array = array('-1'=> __('-- is Visiable --'), '1'=>__('Yes'), '0'=>__('No'));
$status_selection_array = array('-1'=> __('-- is Valid --'), '1'=>__('Yes'), '0'=>__('No'));
$page_size = 20;

$display_fields = apify_get_api_item_display_fields();
$filters = apify_generate_filters_from_params(array('item_name', 'visiable', 'status'), $_REQUEST);
$filter_result = apify_get_all_api_item_list_by_filters( $filters, $paged, $page_size );

$total_paged = intval($filter_result['total'] / $page_size) + 1;
?>
<h1><?php echo __('View All Api Item Page'); ?></h1>
<div id="wpwrap">
	<div id="wpbody-content">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br />
</div>
<h2><?php echo __('Api Item'); ?> <a href="admin.php?page=add-api-item" class="add-new-h2"><?php echo __('Add New'); ?></a></h2>

<ul class="subsubsub"> 
	<li class="all"><?php echo __('valid'); ?> <span class="count">(<?php echo $filter_result['valid_total']; ?>)</span> |</li>
	<li class="publish"><?php echo __('invalid'); ?> <span class="count">(<?php echo $filter_result['invalid_total']; ?>)</span></li>
</ul>

<form id="posts-filter" name="posts_filter" action="" method="post">
	<div class="tablenav top">
		<div class="alignleft actions">
			<label for="item_name"><?php echo __('name'); ?>: </label><input type="text" size="" name="item_name" value="" />
			<?php echo apify_draw_pull_down_menu('visiable', $visiable_selection_array, isset($_REQUEST['visiable']) ? $_REQUEST['visiable'] : '-1'); ?>
			<?php echo apify_draw_pull_down_menu('status', $status_selection_array, isset($_REQUEST['status']) ? $_REQUEST['status'] : '-1' ); ?>
			<input type="submit" name="api-item-query-submit" id="api-item-query-submit" class="button-secondary" value="Filter">
		</div>
		<br class="clear">
	</div>	
	<table class="wp-list-table widefat fixed posts" cellspacing="0">
		<thead>
			<tr>	
				<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox"></th>
				<?php foreach( $display_fields as $key=>$title ) { ?>	
				<th scope="col" id="<?php echo $key; ?>" class="manage-column" style=""><span><?php echo $title; ?></span></th>
				<?php } //end foreach ?>
			</tr>
		</thead>
		<tfoot>
			<tr>	
				<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox"></th>
				<?php foreach( $display_fields as $key=>$title ) { ?>	
				<th scope="col" id="<?php echo $key; ?>" class="manage-column" style=""><span><?php echo $title; ?></span></th>
				<?php } //end foreach ?>
			</tr>
		</tfoot>

		<tbody id="the-list">
			<?php 
			if( empty($filter_result['list']) ) { 
				echo __('No Results');
			}
			?>
			<?php foreach( $filter_result['list'] as $key=>$apiItemObj ) { // ?>
			<tr id="api-item-<?php echo $apiItemObj->item_id; ?>" class="status-publish format-standard hentry category-uncategorized alternate iedit author-self" valign="top">
				<th scope="row" class="check-column"><input type="checkbox" name="item_id[]" value="<?php echo $apiItemObj->item_id; ?>"></th>
				<td class="column-id"><?php echo $apiItemObj->item_id; ?></td>
				<td class="column-title"><span><?php echo $apiItemObj->item_name; ?></span>
					<div class="row-actions">
					<?php if( $_REQUEST['page']=='api-item-trash' ) { ?> 
						<span class="del"><a href="admin.php?page=api-item-trash&action=reback&item_id=<?php echo $apiItemObj->item_id; ?>" title="Del">Return</a> </span>
					<?php } else { ?>
						<span class="edit"><a href="admin.php?page=add-api-item&item_id=<?php echo $apiItemObj->item_id; ?>" title="Edit">Edit</a> | </span> <span class="del"><a href="admin.php?page=api-item-trash&action=remove&item_id=<?php echo $apiItemObj->item_id; ?>" title="Del">Remove</a> </span>
					<?php }	?>
					</div>
				</td>
				<td class=""><?php echo $apiItemObj->item_desc_en; ?></td>
				<td class=""><?php echo $apiItemObj->item_desc_zh; ?></td>
				<td class=""><?php echo $type_selection_array[$apiItemObj->item_type]; ?></td>
				<td class=""><?php echo $api_group_selection_array[$apiItemObj->item_group_id]; ?></td>
				<td class=""><?php echo $apiItemObj->item_response; ?></td>
				<td class=""><?php echo ($apiItemObj->visiable) ? __('Yes') : __('No'); ?></td>
				<td class=""><?php echo ($apiItemObj->status) ? __('Yes') : __('No'); ?></td>
				<td class=""><?php echo $apiItemObj->date_added; ?></td>
				<td class=""><?php echo $apiItemObj->last_modified; ?></td>
			</tr>
			<?php } // end foreach?>
		</tbody>
	</table>

	<div class="tablenav bottom">
		<div class="alignleft actions">
			<select name="action2">
				<option value="-1" selected="selected"><?php echo __('Bulk Actions'); ?></option>
				<option value="edit" class="hide-if-no-js"><?php echo __('Edit'); ?></option>
				<option value="trash"><?php echo ($_REQUEST['page'] == 'api-item-trash') ? __('Return to List') : __('Move to Trash'); ?></option>
			</select> 
			<input type="submit" name="action2Submit" id="bulk-submit" class="button-secondary action" value="Apply">
		</div>
		<div class="alignleft actions"></div>

		<div class="tablenav-pages">
			<span class="displaying-num"><?php echo $filter_result['total']; ?> items</span>
			<span class="pagination-links"> 
				<a class="first-page <?php echo (intval($paged) > 1) ? '' : 'disabled'; ?>"
					title="Go to the first page"
					href="javascript:document.getElementById('paged').value = 1; document.forms['posts_filter'].submit();"><<</a>
				<a class="prev-page <?php echo (intval($paged) > 1) ? '' : 'disabled'; ?>"
					title="Go to the previous page"
					href="javascript:document.getElementById('paged').value = <?php echo intval($paged)-1; ?>; document.forms['posts_filter'].submit();"><</a>
				<span class="paging-input">
					<input class="current-page" title="Current page" type="text" id="paged" name="paged" value="<?php echo intval($paged); ?>" size="2"> of 
					<span class="total-pages"><?php echo $total_paged; ?></span>
				</span> 
				<a class="next-page <?php echo ($total_paged > intval($paged)) ? '' : 'disabled'; ?>"
					title="Go to the next page"
					href="javascript:document.getElementById('paged').value = <?php echo intval($paged)+1; ?>; document.forms['posts_filter'].submit();">></a>
				<a class="last-page <?php echo ($total_paged > intval($paged)) ? '' : 'disabled'; ?>"
					title="Go to the last page"
					href="javascript:document.getElementById('paged').value = <?php echo $total_paged; ?>; document.forms['posts_filter'].submit();">>></a>
			</span>
		</div>
		<br class="clear">
	</div>

</form>
	
<br class="clear">
</div>
</div>
