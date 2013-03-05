<?php
/**
 * 后台添加或是编辑app Admin页面
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
$admin_genger_array = array('-1'=> __('-- Unknow --'), 'm'=>__('Male'), 'f'=>__('Female'));

/**
 * 获取数据
 */
if( isset($_GET['app_admin_id']) && (intval($_GET['app_admin_id']) > 0) ) {
	$title = __('Edit Existing');
	$hidden_action = 'update';
	$hidden_app_admin_id = intval($_GET['app_admin_id']);
	$filters = apify_generate_filters_from_params(array('app_admin_id'), array('app_admin_id'=>intval($_GET['app_admin_id'])));
	$appAdminObj = apify_get_app_admin_by_filters( $filters );
} else {
	$title = __('Add New');
	$hidden_action = 'insert';
	$hidden_app_admin_id = 0;
	$appAdminObj = new stdclass();
}
/**
 * 表单提交处理
 */
if( !empty($_POST['action']) && ($_POST['action'] == 'update') ) { //update
	//validate
	$errors = apify_validate_app_admin($_POST, 'update');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$result = apify_update_app_admin( $_POST, intval($_POST['app_admin_id']) );
	}
	$message = ($result) ? __('Add Success!') : 'Add Failed';
} elseif( !empty($_POST['action']) && ($_POST['action'] == 'insert') ) { //insert
	//validate
	$errors = apify_validate_app_admin($_POST, 'insert');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$new_app_admin_id = apify_insert_app_admin($_POST);
	}
	$message = (intval($new_app_admin_id) > 0) ? __('Add Success!') : 'Add Failed';
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

<form id="app-admin-form" action="" method="POST">
	<input type="hidden" name="action" value="<?php echo $hidden_action; ?>" />
	<input type="hidden" name="app_admin_id" value="<?php echo $hidden_app_admin_id; ?>" />
	
	<table class="form-table">
		<tr>
			<th><label for="app_admin_email"><?php echo __('App Admin Email'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="app_admin_email" id="app_admin_email" value="<?php echo esc_attr($appAdminObj->app_admin_email); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_admin_password"><?php echo __('App Admin Password'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="app_admin_password" id="app_admin_password" value="<?php echo esc_attr($appAdminObj->app_admin_password); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_admin_name"><?php echo __('App Admin Name'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="app_admin_name" id="app_admin_name" value="<?php echo esc_attr($appAdminObj->app_admin_name); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_admin_phone"><?php echo __('App Admin Phone'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="app_admin_phone" id="app_admin_phone" value="<?php echo esc_attr($appAdminObj->app_admin_phone) ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_admin_gender"><?php echo __('App Admin Gender'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('app_admin_gender', $admin_genger_array, $appAdminObj->app_admin_gender, 'id="app_admin_gender"' ); ?></td>
		</tr>
		<tr>
			<th><?php echo __('Verified'); ?></th>
			<td><label for="verified"><input type="checkbox" name="verified" id="verified" value="1" <?php checked('1', $appAdminObj->verified); ?>/> <?php echo __('Verified This App Admin.'); ?></label></td>
		</tr>
		<tr>
			<th><?php echo __('Status'); ?></th>
			<td><label for="status"><input type="checkbox" name="status" id="status" value="1" <?php checked('1', $appAdminObj->status); ?>/> <?php echo __('Enable This App Admin.'); ?></label></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save'); ?>"></p>
</form>
</div>
