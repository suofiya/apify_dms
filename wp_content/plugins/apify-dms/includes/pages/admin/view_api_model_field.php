<?php
/**
 * 后台查看可用api ModelField页面
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
$api_data_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_name');
$api_data_type_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_type');
$api_data_model_selection_array = apify_get_api_data_model_selection(array('status'=>1, 'visiable'=>1), 'data_model_name');
$visiable_selection_array = array('-1'=> __('-- is Visiable --'), '1'=>__('Yes'), '0'=>__('No'));
$status_selection_array = array('-1'=> __('-- is Valid --'), '1'=>__('Yes'), '0'=>__('No'));
$page_size = 20;

$display_fields = apify_get_api_model_field_display_fields();
$filters = apify_generate_filters_from_params(array('model_field_name', 'visiable', 'status'), $_REQUEST);
$filter_result = apify_get_all_api_model_field_list_by_filters( $filters, $paged, $page_size );

$total_paged = intval($filter_result['total'] / $page_size) + 1;
?>
<h1><?php echo __('View All Api ModelField Page'); ?></h1>
<div id="wpwrap">
	<div id="wpbody-content">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br />
</div>
<h2><?php echo __('Api ModelField'); ?> <a href="admin.php?page=add-api-model-field" class="add-new-h2"><?php echo __('Add New'); ?></a></h2>

<ul class="subsubsub"> 
	<li class="all"><?php echo __('valid'); ?> <span class="count">(<?php echo $filter_result['valid_total']; ?>)</span> |</li>
	<li class="publish"><?php echo __('invalid'); ?> <span class="count">(<?php echo $filter_result['invalid_total']; ?>)</span></li>
</ul>

<form id="posts-filter" name="posts_filter" action="" method="post">
	<div class="tablenav top">
		<div class="alignleft actions">
			<label for="model_field_name"><?php echo __('name'); ?>: </label><input type="text" size="" name="model_field_name" value="" />
			<?php echo apify_draw_pull_down_menu('visiable', $visiable_selection_array, isset($_REQUEST['visiable']) ? $_REQUEST['visiable'] : '-1'); ?>
			<?php echo apify_draw_pull_down_menu('status', $status_selection_array, isset($_REQUEST['status']) ? $_REQUEST['status'] : '-1' ); ?>
			<input type="submit" name="api-model_field-query-submit" id="api-model_field-query-submit" class="button-secondary" value="Filter">
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
			<?php foreach( $filter_result['list'] as $key=>$apiModelFieldObj ) { // ?>
			<tr id="api-model-field-<?php echo $apiModelFieldObj->model_field_id; ?>" class="status-publish format-standard hentry category-uncategorized alternate iedit author-self" valign="top">
				<th scope="row" class="check-column"><input type="checkbox" name="model_field_id[]" value="<?php echo $apiModelFieldObj->model_field_id; ?>"></th>
				<td class="column-id"><?php echo $apiModelFieldObj->model_field_id; ?></td>
				<td class="column-title"><span><?php echo $apiModelFieldObj->model_field_name; ?></span>
					<div class="row-actions">
					<?php if( $_REQUEST['page']=='api-model-field-trash' ) { ?> 
						<span class="del"><a href="admin.php?page=api-model_field-trash&action=reback&model_field_id=<?php echo $apiModelFieldObj->model_field_id; ?>" title="Del">Return</a> </span>
					<?php } else { ?>
						<span class="edit"><a href="admin.php?page=add-api-model-field&model_field_id=<?php echo $apiModelFieldObj->model_field_id; ?>" title="Edit">Edit</a> | </span> <span class="del"><a href="admin.php?page=api-model-field-trash&action=remove&model_field_id=<?php echo $apiModelFieldObj->model_field_id; ?>" title="Del">Remove</a> </span>
					<?php }	?>
					</div>
				</td>
				<td class=""><?php echo $apiModelFieldObj->model_field_desc_en; ?></td>
				<td class=""><?php echo $apiModelFieldObj->model_field_desc_zh; ?></td>
				<td class="">
					<?php
					/**
					 * display base or Object
					 */
					$api_data_type_type = intval( $api_data_type_type_selection_array[$apiModelFieldObj->model_field_data_type_id] );
					if( in_array( $api_data_type_type, array(9, 91) ) ) { //1 basic, 9 model, 91 model list
						$data_type_name = $api_data_model_selection_array[$apiModelFieldObj->model_field_model_id];
						if( $api_data_type_type == 91 ) {
							$data_type_name .= '[]';	
						}
						echo $data_type_name;
					} else {
						$data_type_name = $api_data_type_selection_array[$apiModelFieldObj->model_field_data_type_id];
						echo $api_data_type_selection_array[$apiModelFieldObj->model_field_data_type_id]; 
					}
					?>
				</td>
				<td class=""><?php echo $api_data_model_selection_array[$apiModelFieldObj->data_model_id]; ?></td>
				<td class=""><?php echo $apiModelFieldObj->model_field_sample; ?></td>
				<td class=""><?php echo ($apiModelFieldObj->visiable) ? __('Yes') : __('No'); ?></td>
				<td class=""><?php echo ($apiModelFieldObj->status) ? __('Yes') : __('No'); ?></td>
				<td class=""><?php echo $apiModelFieldObj->date_added; ?></td>
				<td class=""><?php echo $apiModelFieldObj->last_modified; ?></td>
			</tr>
			<?php } // end foreach?>
		</tbody>
	</table>

	<div class="tablenav bottom">
		<div class="alignleft actions">
			<select name="action2">
				<option value="-1" selected="selected"><?php echo __('Bulk Actions'); ?></option>
				<option value="edit" class="hide-if-no-js"><?php echo __('Edit'); ?></option>
				<option value="trash"><?php echo ($_REQUEST['page'] == 'api-model-field-trash') ? __('Return to List') : __('Move to Trash'); ?></option>
			</select> 
			<input type="submit" name="action2Submit" id="bulk-submit" class="button-secondary action" value="Apply">
		</div>
		<div class="alignleft actions"></div>

		<div class="tablenav-pages">
			<span class="displaying-num"><?php echo $filter_result['total']; ?> model_fields</span>
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
