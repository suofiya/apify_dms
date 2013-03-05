<?php
/**
 * 后台添加或是编辑api ErrorCode页面
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
$errorcode_group_selection_array = apify_get_api_errorcode_group_selection(array('status'=>1, 'visiable'=>1), 'error_code_group_name');
/**
 * 获取数据
 */
if( isset($_GET['error_code_id']) && (intval($_GET['error_code_id']) > 0) ) {
	$title = __('Edit Existing');
	$hidden_action = 'update';
	$hidden_error_code_id = intval($_GET['error_code_id']);
	$filters = apify_generate_filters_from_params(array('error_code_id'), array('error_code_id'=>intval($_GET['error_code_id'])));
	$apiErrorCodeObj = apify_get_api_errorcode_by_filters( $filters );
} else {
	$title = __('Add New');
	$hidden_action = 'insert';
	$hidden_error_code_id = 0;
	$apiErrorCodeObj = new stdclass();
}
/**
 * 表单提交处理
 */
if( !empty($_POST['action']) && ($_POST['action'] == 'update') ) { //update
	//validate
	$errors = apify_validate_api_errorcode($_POST, 'update');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$result = apify_update_api_errorcode( $_POST, intval($_POST['error_code_id']) );
	}
	$message = ($result) ? __('Add Success!') : 'Add Failed';
} elseif( !empty($_POST['action']) && ($_POST['action'] == 'insert') ) { //insert
	//validate
	$errors = apify_validate_api_errorcode($_POST, 'insert');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$new_errorcode_id = apify_insert_api_errorcode($_POST);
	}
	$message = (intval($new_errorcode_id) > 0) ? __('Add Success!') : 'Add Failed';
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

<form id="errorcode-form" action="" method="POST">
	<input type="hidden" name="action" value="<?php echo $hidden_action; ?>" />
	<input type="hidden" name="error_code_id" value="<?php echo $hidden_error_code_id; ?>" />
	
	<table class="form-table">
		<tr>
			<th><label for="error_code_key"><?php echo __('ErrorCode Key'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="error_code_key" id="error_code_key" value="<?php echo esc_attr($apiErrorCodeObj->error_code_key); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="error_code_group_id"><?php echo __('ErrorCode Group'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('error_code_group_id', $errorcode_group_selection_array, $apiErrorCodeObj->error_code_group_id, 'id="error_code_group_id"' ); ?></td>
		</tr>		
		<tr>
			<th><label for="error_code_desc_en"><?php echo __('ErrorCode Description--english'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="error_code_desc_en" id="errorcode_desc_en" value="<?php echo esc_attr($apiErrorCodeObj->error_code_desc_en); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="error_code_desc_zh"><?php echo __('ErrorCode Description--chinese'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="error_code_desc_zh" id="error_code_desc_zh" value="<?php echo esc_attr($apiErrorCodeObj->error_code_desc_zh); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="error_code_note"><?php echo __('ErrorCode Note'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="error_code_note" id="error_code_note" value="<?php echo esc_attr($apiErrorCodeObj->error_code_note) ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><?php echo __('Visiable'); ?></th>
			<td><label for="visiable"><input type="checkbox" name="visiable" id="visiable" value="1" <?php checked('1', $apiErrorCodeObj->visiable); ?>/> <?php echo __('Visiable'); ?></label></td>
		</tr>
		<tr>
			<th><?php echo __('Status'); ?></th>
			<td><label for="status"><input type="checkbox" name="status" id="status" value="1" <?php checked('1', $apiErrorCodeObj->status); ?>/> <?php echo __('Enable'); ?></label></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save'); ?>"></p>
</form>
</div>
