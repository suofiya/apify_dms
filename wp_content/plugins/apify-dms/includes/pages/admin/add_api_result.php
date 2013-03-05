<?php
/**
 * 后台添加或是编辑api Result页面
 *
 * @package    includes/pages/
 * @subpackage admin
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 *
 * TODO js效果实现
 */
?>
<?php 
/**
 * business logic
 */
$api_data_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_name');
$api_data_type_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_type');
$api_data_model_selection_array = apify_get_api_data_model_selection(array('status'=>1, 'visiable'=>1), 'data_model_name');
$api_item_selection_array = apify_get_api_item_selection(array('status'=>1, 'visiable'=>1), 'item_name');
/**
 * 获取数据
 */
if( isset($_GET['result_id']) && (intval($_GET['result_id']) > 0) ) {
	$title = __('Edit Existing');
	$hidden_action = 'update';
	$hidden_result_id = intval($_GET['result_id']);
	$filters = apify_generate_filters_from_params(array('result_id'), array('result_id'=>intval($_GET['result_id'])));
	$apiResultObj = apify_get_api_result_by_filters( $filters );
} else {
	$title = __('Add New');
	$hidden_action = 'insert';
	$hidden_result_id = 0;
	$apiResultObj = new stdclass();
}
/**
 * 表单提交处理
 */
if( !empty($_POST['action']) && ($_POST['action'] == 'update') ) { //update
	//validate
	$errors = apify_validate_api_result($_POST, 'update');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$result = apify_update_api_result( $_POST, intval($_POST['result_id']) );
	}
	$message = ($result) ? __('Add Success!') : 'Add Failed';
} elseif( !empty($_POST['action']) && ($_POST['action'] == 'insert') ) { //insert
	//validate
	$errors = apify_validate_api_result($_POST, 'insert');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$new_result_id = apify_insert_api_result($_POST);
	}
	$message = (intval($new_result_id) > 0) ? __('Add Success!') : 'Add Failed';
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

<form id="result-form" action="" method="POST">
	<input type="hidden" name="action" value="<?php echo $hidden_action; ?>" />
	<input type="hidden" name="result_id" value="<?php echo $hidden_result_id; ?>" />
	
	<table class="form-table">
		<tr>
			<th><label for="result_name"><?php echo __('Result name'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="result_name" id="result_name" value="<?php echo esc_attr($apiResultObj->result_name); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="result_data_type_id"><?php echo __('Type'); ?> <span class="description"><?php echo __('(required)'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('result_data_type_id', $api_data_type_selection_array, $apiResultObj->result_data_type_id, 'id="DataTypeSelect"' ); ?></td>
		</tr>
		<tr id="modelFieldModelIdDiv" style="display:none;">
			<th><label for="result_model_id"><?php echo __('Model'); ?> <span class="description"><?php echo __('(NOTE: 当Type为Model或是Model[]时，该选项必选)'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('result_model_id', $api_data_model_selection_array, $apiResultObj->result_model_id, 'id="result_model_id"' ); ?></td>
		</tr>
		<tr>
			<th><label for="item_id"><?php echo __('API Item'); ?> <span class="description"><?php echo __('(required)'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('item_id', $api_item_selection_array, $apiResultObj->item_id, 'id="item_id"' ); ?></td>
		</tr>
		<tr>
			<th><label for="result_desc_en"><?php echo __('Result Description--english'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="result_desc_en" id="result_desc_en" value="<?php echo esc_attr($apiResultObj->result_desc_en); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="result_desc_zh"><?php echo __('Result Description--chinese'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="result_desc_zh" id="result_desc_zh" value="<?php echo esc_attr($apiResultObj->result_desc_zh); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><?php echo __('Visiable'); ?></th>
			<td><label for="visiable"><input type="checkbox" name="visiable" id="visiable" value="1" <?php checked('1', $apiResultObj->visiable); ?>/> <?php echo __('Visiable'); ?></label></td>
		</tr>
		<tr>
			<th><?php echo __('Status'); ?></th>
			<td><label for="status"><input type="checkbox" name="status" id="status" value="1" <?php checked('1', $apiResultObj->status); ?>/> <?php echo __('Enable'); ?></label></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save'); ?>"></p>
</form>
</div>
