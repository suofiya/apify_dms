<?php
/**
 * 后台添加或是编辑api Item页面
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
/**
 * 获取数据
 */
if( isset($_GET['item_id']) && (intval($_GET['item_id']) > 0) ) {
	$title = __('Edit Existing');
	$hidden_action = 'update';
	$hidden_item_id = intval($_GET['item_id']);
	$filters = apify_generate_filters_from_params(array('item_id'), array('item_id'=>intval($_GET['item_id'])));
	$apiItemObj = apify_get_api_item_by_filters( $filters );
} else {
	$title = __('Add New');
	$hidden_action = 'insert';
	$hidden_item_id = 0;
	$apiItemObj = new stdclass();
}
/**
 * 表单提交处理
 */
if( !empty($_POST['action']) && ($_POST['action'] == 'update') ) { //update
	//validate
	$errors = apify_validate_api_item($_POST, 'update');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$result = apify_update_api_item( $_POST, intval($_POST['item_id']) );
	}
	$message = ($result) ? __('Add Success!') : 'Add Failed';
} elseif( !empty($_POST['action']) && ($_POST['action'] == 'insert') ) { //insert
	//validate
	$errors = apify_validate_api_item($_POST, 'insert');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$new_item_id = apify_insert_api_item($_POST);
	}
	$message = (intval($new_item_id) > 0) ? __('Add Success!') : 'Add Failed';
}
?>
<div id="main">
<?php if ( isset( $message ) && !empty( $message ) ) { ?>
<div id="message" class="updated"><p><strong><?php echo $message; ?></strong></p></div>
<?php } ?>
<?php if ( is_object( $errors ) && $errors->get_error_codes() ) { ?>
<div class="error"><p><?php echo implode( "</p>\n<p>", $errors->get_error_messages() ); ?></p></div>
<?php } ?>
<div id="wpwrap">
	<div id="wpbody-content">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br />
</div>
<h2><?php echo esc_html( $title ); ?></h2>

<form id="item-form" action="" method="POST">
	<input type="hidden" name="action" value="<?php echo $hidden_action; ?>" />
	<input type="hidden" name="item_id" value="<?php echo $hidden_item_id; ?>" />
	
	<table class="form-table">
		<tr>
			<th><label for="item_name"><?php echo __('Item name'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="item_name" id="item_name" value="<?php echo esc_attr($apiItemObj->item_name); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="item_type"><?php echo __('Type'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('item_type', $type_selection_array, $apiItemObj->item_type, 'id="item_type"' ); ?></td>
		</tr>
		<tr>
			<th><label for="item_group_id"><?php echo __('Group'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('item_group_id', $api_group_selection_array, $apiItemObj->item_group_id, 'id="item_group_id"' ); ?></td>
		</tr>
		<tr>
			<th><label for="item_desc_en"><?php echo __('Item Description--english'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="item_desc_en" id="item_desc_en" value="<?php echo esc_attr($apiItemObj->item_desc_en); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="item_desc_zh"><?php echo __('Item Description--chinese'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="item_desc_zh" id="item_desc_zh" value="<?php echo esc_attr($apiItemObj->item_desc_zh); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="item_note_en"><?php echo __('Item Note--english'); ?></label></th>
			<td><input type="text" name="item_note_en" id="item_note_en" value="<?php echo esc_attr($apiItemObj->item_note_en); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="item_note_zh"><?php echo __('Item Note--chinese'); ?></label></th>
			<td><input type="text" name="item_note_zh" id="item_note_zh" value="<?php echo esc_attr($apiItemObj->item_note_zh); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="item_response"><?php echo __('response'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="item_response" id="item_response" value="<?php echo esc_attr($apiItemObj->item_response); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="item_response_example_json"><?php echo __('json respose example'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><textarea class="wp-editor-area" rows="10" tabindex="1" cols="100" name="item_response_example_json" id="item_response_example_json"><?php echo esc_attr(stripslashes($apiItemObj->item_response_example_json)); ?></textarea></td>
		</tr>
		<tr>
			<th><label for="item_response_example_xml"><?php echo __('xml respose example'); ?></span></label></th>
			<td><input type="text" name="item_response_example_xml" id="item_response_example_xml" value="<?php echo esc_attr($apiItemObj->item_response_example_xml); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="item_model_ids"><?php echo __('Item Model ids'); ?> <span class="description"><?php echo __('[以,隔开的数据对象ID列表]'); ?></span></label></th>
			<td><input type="text" name="item_model_ids" id="item_model_ids" value="<?php echo esc_attr($apiItemObj->item_model_ids); ?>" class="regular-text" /></td>
		</tr>	
		<tr>
			<th><label for="item_errorcode_ids"><?php echo __('Item ErrorCode ids'); ?> <span class="description"><?php echo __('[以,隔开的错误码ID列表]'); ?></span></label></th>
			<td><input type="text" name="item_errorcode_ids" id="item_errorcode_ids" value="<?php echo esc_attr($apiItemObj->item_errorcode_ids); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><?php echo __('Visiable'); ?></th>
			<td><label for="visiable"><input type="checkbox" name="visiable" id="visiable" value="1" <?php checked('1', $apiItemObj->visiable); ?>/> <?php echo __('Visiable'); ?></label></td>
		</tr>
		<tr>
			<th><?php echo __('Status'); ?></th>
			<td><label for="status"><input type="checkbox" name="status" id="status" value="1" <?php checked('1', $apiItemObj->status); ?>/> <?php echo __('Enable'); ?></label></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save'); ?>"></p>
</form>
</div>
