<?php
/**
 * 业务逻辑helper函数集
 *
 * @package    includes
 * @subpackage functions
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */

/**
 * 后台管理界面显示的app client字段
 */
function apify_get_app_client_admin_display_fields() {
	$display_fields = array();
	
	$display_fields['app_client_id'] = __('ID');
	$display_fields['app_client_name'] = __('name');
	$display_fields['app_client_desc_en'] = __('desc_en');
	$display_fields['app_client_desc_zh'] = __('desc_zh');
	$display_fields['app_client_key'] = __('app_key');
	$display_fields['app_client_secret'] = __('app_secret');
	$display_fields['verified'] = __('verified');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的app admin字段
 */
function apify_get_app_admin_admin_display_fields() {
	$display_fields = array();
	
	$display_fields['app_client_id'] = __('ID');
	$display_fields['app_admin_email'] = __('email');
	$display_fields['app_admin_name'] = __('name');
	$display_fields['app_admin_phone'] = __('phone');
	$display_fields['app_admin_gender'] = __('gender');
	$display_fields['verified'] = __('verified');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的api DataType字段
 */
function apify_get_api_data_type_display_fields() {
	$display_fields = array();
	
	$display_fields['data_type_id'] = __('ID');
	$display_fields['data_type_name'] = __('name');
	$display_fields['data_type_type'] = __('type');
	$display_fields['data_type_desc_en'] = __('desc_en');
	$display_fields['data_type_desc_zh'] = __('desc_zh');
	$display_fields['data_type_sample'] = __('sample');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的api group字段
 */
function apify_get_api_group_display_fields() {
	$display_fields = array();
	
	$display_fields['group_id'] = __('ID');
	$display_fields['group_name'] = __('name');
	$display_fields['group_title_en'] = __('title_en');
	$display_fields['group_title_zh'] = __('title_zh');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的api item字段
 */
function apify_get_api_item_display_fields() {
	$display_fields = array();
	
	$display_fields['item_id'] = __('ID');
	$display_fields['item_name'] = __('name');
	$display_fields['item_desc_en'] = __('desc_en');
	$display_fields['item_desc_zh'] = __('desc_zh');
	$display_fields['item_type'] = __('type');
	$display_fields['item_group_id'] = __('Group');
	$display_fields['item_response'] = __('response');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的sys parameter字段
 */
function apify_get_sys_parameter_display_fields() {
	$display_fields = array();
	
	$display_fields['parameter_id'] = __('ID');
	$display_fields['parameter_name'] = __('name');
	$display_fields['parameter_desc_en'] = __('desc_en');
	$display_fields['parameter_desc_zh'] = __('desc_zh');
	$display_fields['parameter_data_type_id'] = __('type');
	$display_fields['parameter_required_mode'] = __('mode');
	$display_fields['parameter_rule'] = __('rule');
	$display_fields['parameter_enum'] = __('enum');
	$display_fields['parameter_default_value'] = __('default');
	$display_fields['parameter_sample'] = __('example');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');

	return $display_fields;
}

/**
 * 后台管理界面显示的api parameter字段
 */
function apify_get_api_parameter_display_fields() {
	$display_fields = array();
	
	$display_fields['parameter_id'] = __('ID');
	$display_fields['parameter_name'] = __('name');
	$display_fields['parameter_desc_en'] = __('desc_en');
	$display_fields['parameter_desc_zh'] = __('desc_zh');
	$display_fields['parameter_data_type_id'] = __('type');
	$display_fields['parameter_required_mode'] = __('mode');
	$display_fields['item_id'] = __('api_item');
	$display_fields['parameter_rule'] = __('rule');
	$display_fields['parameter_enum'] = __('enum');
	$display_fields['parameter_default_value'] = __('default');
	$display_fields['parameter_minlength'] = __('minlength');
	$display_fields['parameter_maxlength'] = __('maxlength');
	$display_fields['parameter_min'] = __('min');
	$display_fields['parameter_max'] = __('max');	
	$display_fields['parameter_sample'] = __('example');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');

	return $display_fields;
}

/**
 * 后台管理界面显示的api result字段
 */
function apify_get_api_result_display_fields() {
	$display_fields = array();
	
	$display_fields['result_id'] = __('ID');
	$display_fields['result_name'] = __('name');
	$display_fields['result_data_type_id'] = __('type');
	$display_fields['result_desc_en'] = __('desc_en');
	$display_fields['result_desc_zh'] = __('desc_zh');	
	$display_fields['item_id'] = __('api_item');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的api dataModel字段 
 */
function apify_get_api_data_model_display_fields() {
	$display_fields = array();
	
	$display_fields['data_model_id'] = __('ID');
	$display_fields['data_model_name'] = __('name');
	$display_fields['data_model_desc_en'] = __('desc_en');
	$display_fields['data_model_desc_zh'] = __('desc_zh');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的api model field字段
 */
function apify_get_api_model_field_display_fields() {
	$display_fields = array();
	
	$display_fields['model_field_id'] = __('ID');
	$display_fields['model_field_name'] = __('name');
	$display_fields['model_field_desc_en'] = __('desc_en');
	$display_fields['model_field_desc_zh'] = __('desc_zh');
	$display_fields['model_field_data_type_id'] = __('data_type');	
	$display_fields['data_model_id'] = __('data_model');
	$display_fields['model_field_sample'] = __('example');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的api errorcode group字段
 */
function apify_get_api_errorcode_group_display_fields() {
	$display_fields = array();
	
	$display_fields['error_code_group_id'] = __('ID');
	$display_fields['error_code_group_name'] = __('name');
	$display_fields['error_code_group_desc_en'] = __('desc_en');
	$display_fields['error_code_group_desc_zh'] = __('desc_zh');
	$display_fields['error_code_group_note'] = __('note');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}

/**
 * 后台管理界面显示的api model field字段
 */
function apify_get_api_errorcode_display_fields() {
	$display_fields = array();
	
	$display_fields['error_code_id'] = __('ID');
	$display_fields['error_code_key'] = __('key');
	$display_fields['error_code_desc_en'] = __('desc_en');
	$display_fields['error_code_desc_zh'] = __('desc_zh');
	$display_fields['error_code_group_id'] = __('group');
	$display_fields['error_code_note'] = __('note');
	$display_fields['visiable'] = __('visiable');
	$display_fields['status'] = __('status');
	$display_fields['date_added'] = __('date_added');
	$display_fields['last_modified'] = __('last_modified');

	return $display_fields;
}
