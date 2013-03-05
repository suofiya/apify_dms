<?php
/**
 * 后台添加或是编辑api DataType页面
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
$data_type_type_selection_array = array('-1'=> __('-- None --'), '1'=>__('basic'), '2'=>__('Model'), '21'=>__('Model List'));
/**
 * 获取数据
 */
if( isset($_GET['data_type_id']) && (intval($_GET['data_type_id']) > 0) ) {
	$title = __('Edit Existing');
	$hidden_action = 'update';
	$hidden_data_type_id = intval($_GET['data_type_id']);
	$filters = apify_generate_filters_from_params(array('data_type_id'), array('data_type_id'=>intval($_GET['data_type_id'])));
	$apiDataTypeObj = apify_get_api_data_type_by_filters( $filters );
} else {
	$title = __('Add New');
	$hidden_action = 'insert';
	$hidden_data_type_id = 0;
	$apiDataTypeObj = new stdclass();
}
/**
 * 表单提交处理
 */
if( !empty($_POST['action']) && ($_POST['action'] == 'update') ) { //update
	//validate
	$errors = apify_validate_api_data_type($_POST, 'update');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$result = apify_update_api_data_type( $_POST, intval($_POST['data_type_id']) );
	}
	$message = ($result) ? __('Add Success!') : 'Add Failed';
} elseif( !empty($_POST['action']) && ($_POST['action'] == 'insert') ) { //insert
	//validate
	$errors = apify_validate_api_data_type($_POST, 'insert');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$new_data_type_id = apify_insert_api_data_type($_POST);
	}
	$message = (intval($new_data_type_id) > 0) ? __('Add Success!') : 'Add Failed';
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

<form id="data-type-form" action="" method="POST">
	<input type="hidden" name="action" value="<?php echo $hidden_action; ?>" />
	<input type="hidden" name="data_type_id" value="<?php echo $hidden_data_type_id; ?>" />
	
	<table class="form-table">
		<tr>
			<th><label for="data_type_name"><?php echo __('DataType Name'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="data_type_name" id="data_type_name" value="<?php echo esc_attr($apiDataTypeObj->data_type_name); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="data_type_type"><?php echo __('Type'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('data_type_type', $data_type_type_selection_array, $apiDataTypeObj->data_type_type, 'id="data_type_type"' ); ?></td>
		</tr>
		<tr>
			<th><label for="data_type_desc_en"><?php echo __('DataType Description--english'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="data_type_desc_en" id="data_type_desc_en" value="<?php echo esc_attr($apiDataTypeObj->data_type_desc_en); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="data_type_desc_zh"><?php echo __('DataType Description--chinese'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="data_type_desc_zh" id="data_type_desc_zh" value="<?php echo esc_attr($apiDataTypeObj->data_type_desc_zh); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="data_type_sample"><?php echo __('DataType Sample'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="data_type_sample" id="data_type_sample" value="<?php echo esc_attr($apiDataTypeObj->data_type_sample) ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><?php echo __('Visiable'); ?></th>
			<td><label for="visiable"><input type="checkbox" name="visiable" id="visiable" value="1" <?php checked('1', $apiDataTypeObj->visiable); ?>/> <?php echo __('Visiable'); ?></label></td>
		</tr>
		<tr>
			<th><?php echo __('Status'); ?></th>
			<td><label for="status"><input type="checkbox" name="status" id="status" value="1" <?php checked('1', $apiDataTypeObj->status); ?>/> <?php echo __('Enable'); ?></label></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save'); ?>"></p>
</form>
</div>
