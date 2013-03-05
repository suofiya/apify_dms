<?php
/**
 * 后台添加或是编辑app client页面
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
 * 获取数据
 */
if( isset($_GET['app_client_id']) && (intval($_GET['app_client_id']) > 0) ) {
	$title = __('Edit Existing');
	$hidden_action = 'update';
	$hidden_app_client_id = intval($_GET['app_client_id']);
	//获取申请client的admin
	$unverified_app_admin_array = array();
	//获取client信息
	$filters = apify_generate_filters_from_params(array('app_client_id'), array('app_client_id'=>intval($_GET['app_client_id'])));
	$appClientObj = apify_get_app_client_by_filters( $filters );
} else {
	$title = __('Add New');
	$hidden_action = 'insert';
	$hidden_app_client_id = 0;
	//获取申请client的admin
	$admin_filters = apify_generate_filters_from_params(array('verified', 'status'), array('verified'=>0, 'status'=>1));
	$app_admin_filter_result = apify_get_all_app_admin_list_by_filters( $admin_filters );
	if( isset($app_admin_filter_result['list']) && !empty($app_admin_filter_result['list']) ) {
		$unverified_app_admin_array = array( '-1' => __('-- None --') );
		foreach( $app_admin_filter_result['list'] as $appAdminObj ) {
			$unverified_app_admin_array[$appAdminObj->app_admin_id] = $appAdminObj->app_admin_email;
		}
	}
	//
	$appClientObj = new stdclass();
}

/**
 * 表单提交处理
 */
if( !empty($_POST['action']) && ($_POST['action'] == 'update') ) { //update
	//validate
	$errors = apify_validate_app_client($_POST, 'update');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$result = apify_update_app_client( $_POST, intval($_POST['app_client_id']) );
	}
	$message = ($result) ? __('Add Success!') : 'Add Failed';
} elseif( !empty($_POST['action']) && ($_POST['action'] == 'insert') ) { //insert
	//validate
	$errors = apify_validate_app_client($_POST, 'insert');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$new_app_client_id = apify_insert_app_client($_POST);
	}
	$message = (intval($new_app_client_id) > 0) ? __('Add Success!') : 'Add Failed';
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

<form id="app-client-form" action="" method="POST">
	<input type="hidden" name="action" value="<?php echo $hidden_action; ?>" />
	<input type="hidden" name="app_client_id" value="<?php echo $hidden_app_client_id ?>" />

	<table class="form-table">
		<tr>
			<th><label for="app_client_name"><?php echo __('App Client Name'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="app_client_name" id="app_client_name" value="<?php echo esc_attr($appClientObj->app_client_name); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_client_desc_en"><?php echo __('App Client Description--English'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="app_client_desc_en" id="app_client_desc_en" value="<?php echo esc_attr($appClientObj->app_client_desc_en); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_client_desc_zh"><?php echo __('App Client Description--Chinese'); ?></label></th>
			<td><input type="text" name="app_client_desc_zh" id="app_client_desc_zh" value="<?php echo esc_attr($appClientObj->app_client_desc_zh); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_client_key"><?php echo __('App Key'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td>
				<input type="text" name="app_client_key" id="app_client_key" value="<?php echo esc_attr($appClientObj->app_client_key) ?>" class="regular-text" />
				<input type="button" name="app_key_secret_btn" id="app_key_secret_btn" class="button-primary" value="<?php echo __('自动生成Key&Secret'); ?>">
			</td>
		</tr>
		<tr>
			<th><label for="app_client_secret"><?php echo __('App Secret'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="app_client_secret" id="app_client_secret" value="<?php echo esc_attr($appClientObj->app_client_secret) ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="app_admin_id"><?php echo __('App Admin'); ?></label></th>
			<?php if( !empty($unverified_app_admin_array) ) { //新建下拉， ?>
			<td><?php echo apify_draw_pull_down_menu('app_admin_id', $unverified_app_admin_array, $appClientObj->app_admin_id, 'id="app_admin_id"' ); ?></td>
			<?php } else { //更新不可修改 ?>
			<td><input type="text" name="app_admin_id" id="app_admin_id" value="<?php echo esc_attr($appClientObj->app_admin_id); ?>" readonly="true" class="regular-text" /> <span class="description"><?php echo __('Admin cannot be changed.'); ?></span></td>
			<?php } ?>
		</tr>
		<tr>
			<th><?php echo __('Verified'); ?></th>
			<td><label for="verified"><input type="checkbox" name="verified" id="verified" value="1" <?php checked('1', $appClientObj->verified); ?>/> <?php echo __('Verified This App Client.'); ?></label></td>
		</tr>
		<tr>
			<th><?php echo __('Status'); ?></th>
			<td><label for="status"><input type="checkbox" name="status" id="status" value="1" <?php checked('1', $appClientObj->status); ?>/> <?php echo __('Enable This App Client.'); ?></label></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save'); ?>"></p>
</form>
</div>
