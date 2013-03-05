<?php
/**
 * 后台添加或是编辑sys Parameter页面
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
$mode_selection_array = array('-1'=> __('-- mode --'), '1'=>__('required'), '2'=>__('optional'), '91'=>__('special required'), '92'=>__('special optional'));
/**
 * 获取数据
 */
if( isset($_GET['parameter_id']) && (intval($_GET['parameter_id']) > 0) ) {
	$title = __('Edit Existing');
	$hidden_action = 'update';
	$hidden_parameter_id = intval($_GET['parameter_id']);
	$filters = apify_generate_filters_from_params(array('parameter_id'), array('parameter_id'=>intval($_GET['parameter_id'])));
	$sysParameterObj = apify_get_sys_parameter_by_filters( $filters );
} else {
	$title = __('Add New');
	$hidden_action = 'insert';
	$hidden_parameter_id = 0;
	$sysParameterObj = new stdclass();
}
/**
 * 表单提交处理
 */
if( !empty($_POST['action']) && ($_POST['action'] == 'update') ) { //update
	//validate
	$errors = apify_validate_sys_parameter($_POST, 'update');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$result = apify_update_sys_parameter( $_POST, intval($_POST['parameter_id']) );
	}
	$message = ($result) ? __('Add Success!') : 'Add Failed';
} elseif( !empty($_POST['action']) && ($_POST['action'] == 'insert') ) { //insert
	//validate
	$errors = apify_validate_sys_parameter($_POST, 'insert');
	if( is_object( $errors ) && false == $errors->get_error_codes() ) {
		$new_parameter_id = apify_insert_sys_parameter($_POST);
	}
	$message = (intval($new_parameter_id) > 0) ? __('Add Success!') : 'Add Failed';
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

<form id="parameter-form" action="" method="POST">
	<input type="hidden" name="action" value="<?php echo $hidden_action; ?>" />
	<input type="hidden" name="parameter_id" value="<?php echo $hidden_parameter_id; ?>" />
	
	<table class="form-table">
		<tr>
			<th><label for="parameter_name"><?php echo __('Parameter name'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="parameter_name" id="parameter_name" value="<?php echo esc_attr($sysParameterObj->parameter_name); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="parameter_data_type_id"><?php echo __('Type'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('parameter_data_type_id', $api_data_type_selection_array, $sysParameterObj->parameter_data_type_id, 'id="parameter_data_type_id"' ); ?></td>
		</tr>
		<tr>
			<th><label for="parameter_required_mode"><?php echo __('Mode'); ?></label></th>
			<td><?php echo apify_draw_pull_down_menu('parameter_required_mode', $mode_selection_array, $sysParameterObj->parameter_required_mode, 'id="parameter_required_mode"' ); ?></td>
		</tr>
		<tr>
			<th><label for="parameter_desc_en"><?php echo __('Parameter Description--english'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="parameter_desc_en" id="parameter_desc_en" value="<?php echo esc_attr($sysParameterObj->parameter_desc_en); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="parameter_desc_zh"><?php echo __('Parameter Description--chinese'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="parameter_desc_zh" id="parameter_desc_zh" value="<?php echo esc_attr($sysParameterObj->parameter_desc_zh); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="parameter_rule"><?php echo __('Rule'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="parameter_rule" id="v" value="<?php echo esc_attr($sysParameterObj->parameter_rule); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="parameter_enum"><?php echo __('Enum'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="parameter_enum" id="parameter_enum" value="<?php echo esc_attr($sysParameterObj->parameter_enum); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="parameter_default_value"><?php echo __('Default Value'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="parameter_default_value" id="parameter_default_value" value="<?php echo esc_attr($sysParameterObj->parameter_default_value); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="parameter_sample"><?php echo __('sample'); ?> <span class="description"><?php echo __('(required)'); ?></span></label></th>
			<td><input type="text" name="parameter_sample" id="parameter_sample" value="<?php echo esc_attr($sysParameterObj->parameter_sample); ?>" class="regular-text" /></td>
		</tr>	
		<tr>
			<th><?php echo __('Visiable'); ?></th>
			<td><label for="visiable"><input type="checkbox" name="visiable" id="visiable" value="1" <?php checked('1', $sysParameterObj->visiable); ?>/> <?php echo __('Visiable'); ?></label></td>
		</tr>
		<tr>
			<th><?php echo __('Status'); ?></th>
			<td><label for="status"><input type="checkbox" name="status" id="status" value="1" <?php checked('1', $sysParameterObj->status); ?>/> <?php echo __('Enable'); ?></label></td>
		</tr>
	</table>
	
	<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php echo __('Save'); ?>"></p>
</form>
</div>
