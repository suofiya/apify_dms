<?php
/**
 * mangager.php
 * 业务逻辑管理函数集
 *
 * @package    includes
 * @subpackage functions
 *
 * @copyright Copyright (c) 2010, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */

/**
 * 检验app admin POST数据
 */
function apify_validate_app_admin($data, $action='insert') {
	$errors = new WP_Error();

	/* check app admin email address */
	if ( empty($data['app_admin_email']) ) {
		$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please enter an e-mail address.' ), array( 'form-field' => 'app_admin_email' ) );
	} elseif ( !is_email($data['app_admin_email']) ) {
		$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The e-mail address isn&#8217;t correct.' ), array( 'form-field' => 'app_admin_email' ) );
	} elseif ( (strtolower($action) == 'insert') && apify_app_admin_email_exists($data['app_admin_email']) ) {
		$errors->add( 'email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.'), array( 'form-field' => 'app_admin_email' ) );
	}

	/* check password */
	if ( empty($data['app_admin_password']) ) {
		$errors->add( 'empty_password', __( '<strong>ERROR</strong>: Please enter password.' ), array( 'form-field' => 'app_admin_password' ) );
	} 

	/* check name */
	if ( empty($data['app_admin_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'app_admin_name' ) );
	} 

	/* check name */
	if ( empty($data['app_admin_phone']) ) {
		$errors->add( 'empty_phone', __( '<strong>ERROR</strong>: Please enter phone.' ), array( 'form-field' => 'app_admin_phone' ) );
	} 

	/* check gender */
	if ( empty($data['app_admin_gender']) || ($data['app_admin_gender'] == '-1') ) {
		$errors->add( 'empty_gender', __( '<strong>ERROR</strong>: Please select gender.' ), array( 'form-field' => 'app_admin_gender' ) );
	} elseif ( !in_array($data['app_admin_gender'], array('m', 'f')) ) {
		$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The gender isn&#8217;t valid.' ), array( 'form-field' => 'app_admin_gender' ) );
	}

	return $errors;
}

/**
 * 检验app client POST数据
 */
function apify_validate_app_client($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['app_client_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'app_client_name' ) );
	} elseif( ( strtolower($action) == 'insert' ) && apify_app_client_name_key_exists($data['app_client_name']) ) {
		$errors->add( 'name_exists', __( '<strong>ERROR</strong>: This name is already registered, please choose another one.' ), array( 'form-field' => 'app_client_name' ) );
	}  

	/* check desc */
	if ( empty($data['app_client_desc_en']) ) {
		$errors->add( 'empty_client_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'app_client_desc_en' ) );
	} 

	/* check app key */
	if ( empty($data['app_client_key']) ) {
		$errors->add( 'empty_client_key', __( '<strong>ERROR</strong>: Please enter app key.' ), array( 'form-field' => 'app_client_key' ) );
	} 

	/* check app secret */
	if ( empty($data['app_client_secret']) ) {
		$errors->add( 'empty_client_secret', __( '<strong>ERROR</strong>: Please enter app secret.' ), array( 'form-field' => 'app_client_secret' ) );
	} 

	/* check admin id */
	if ( empty($data['app_admin_id']) || ($data['app_admin_id'] == '-1') ) {
		$errors->add( 'empty_admin_id', __( '<strong>ERROR</strong>: Please select admin.' ), array( 'form-field' => 'app_admin_id' ) );
	} 

	return $errors;
}

/**
 * 检验api data type POST数据
 */
function apify_validate_api_data_type($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['data_type_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'data_type_name' ) );
	} elseif( ( strtolower($action) == 'insert' ) && apify_api_data_type_name_exists($data['data_type_name']) ) {
		$errors->add( 'name_exists', __( '<strong>ERROR</strong>: This name is already registered, please choose another one.' ), array( 'form-field' => 'data_type_name' ) );
	}

	/* check type */
	if ( empty($data['data_type_type']) || ($data['data_type_type'] == '-1') ) {
		$errors->add( 'empty_type', __( '<strong>ERROR</strong>: Please select admin.' ), array( 'form-field' => 'data_type_type' ) );
	} 	

	/* check desc */
	if ( empty($data['data_type_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'data_type_desc_en' ) );
	} 

	/* check sample */
	if ( empty($data['data_type_sample']) ) {
		$errors->add( 'empty_sample', __( '<strong>ERROR</strong>: Please enter sample.' ), array( 'form-field' => 'data_type_sample' ) );
	} 

	return $errors;
}

/**
 * 检验api group POST数据
 */
function apify_validate_api_group($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['group_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'group_name' ) );
	} elseif( ( strtolower($action) == 'insert' ) && apify_api_group_name_exists($data['group_name']) ) {
		$errors->add( 'name_exists', __( '<strong>ERROR</strong>: This name is already registered, please choose another one.' ), array( 'form-field' => 'group_name' ) );
	}

	/* check desc */
	if ( empty($data['group_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'group_desc_en' ) );
	} 

	return $errors;
}

/**
 * 检验api item POST数据
 */
function apify_validate_api_item($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['item_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'item_name' ) );
	} elseif( ( strtolower($action) == 'insert' ) && apify_api_item_name_exists($data['item_name']) ) {
		$errors->add( 'name_exists', __( '<strong>ERROR</strong>: This name is already registered, please choose another one.' ), array( 'form-field' => 'item_name' ) );
	}

	/* check desc */
	if ( empty($data['item_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'item_desc_en' ) );
	} 

	/* check type */
	if ( empty($data['item_type']) ) {
		$errors->add( 'empty_type', __( '<strong>ERROR</strong>: Please enter sample.' ), array( 'form-field' => 'item_type' ) );
	} 

	/* check type */
	if ( empty($data['item_group_id']) ) {
		$errors->add( 'empty_group_id', __( '<strong>ERROR</strong>: Please enter sample.' ), array( 'form-field' => 'item_group_id' ) );
	} 

	return $errors;
}


/**
 * 检验sys parameter POST数据
 */
function apify_validate_sys_parameter($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['parameter_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'parameter_name' ) );
	} 

	/* check desc */
	if ( empty($data['parameter_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'parameter_desc_en' ) );
	} 

	/* check type */
	if ( empty($data['parameter_data_type_id']) ) {
		$errors->add( 'empty_data_type_id', __( '<strong>ERROR</strong>: Please select type.' ), array( 'form-field' => 'parameter_data_type_id' ) );
	} 


	/* check required_mode */
	if ( empty($data['parameter_required_mode']) ) {
		$errors->add( 'empty_required_mode', __( '<strong>ERROR</strong>: Please select mode.' ), array( 'form-field' => 'parameter_required_mode' ) );
	} 


	return $errors;
}

/**
 * 检验api parameter POST数据
 */
function apify_validate_api_parameter($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['parameter_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'parameter_name' ) );
	} 

	/* check desc */
	if ( empty($data['parameter_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'parameter_desc_en' ) );
	} 

	/* check type */
	if ( empty($data['parameter_data_type_id']) ) {
		$errors->add( 'empty_data_type_id', __( '<strong>ERROR</strong>: Please select type.' ), array( 'form-field' => 'parameter_data_type_id' ) );
	} 


	/* check required_mode */
	if ( empty($data['parameter_required_mode']) ) {
		$errors->add( 'empty_required_mode', __( '<strong>ERROR</strong>: Please select mode.' ), array( 'form-field' => 'parameter_required_mode' ) );
	} 

	/* check item_id */
	if ( empty($data['item_id']) ) {
		$errors->add( 'empty_item_id', __( '<strong>ERROR</strong>: Please select api item.' ), array( 'form-field' => 'item_id' ) );
	} 


	return $errors;
}


/**
 * 检验api result POST数据
 */
function apify_validate_api_result($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['result_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'result_name' ) );
	} 

	/* check desc */
	if ( empty($data['result_desc_en']) ) {
		$errors->add( 'result_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'result_desc_en' ) );
	} 

	/* check type */
	if ( empty($data['result_data_type_id']) ) {
		$errors->add( 'empty_data_type_id', __( '<strong>ERROR</strong>: Please select type.' ), array( 'form-field' => 'result_data_type_id' ) );
	} 

	/* check api item */
	if ( empty($data['item_id']) ) {
		$errors->add( 'empty_item_id', __( '<strong>ERROR</strong>: Please select API.' ), array( 'form-field' => 'item_id' ) );
	} 


	return $errors;
}


/**
 * 检验api data model POST数据
 */
function apify_validate_api_data_model($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['data_model_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'data_model_name' ) );
	} elseif( ( strtolower($action) == 'insert' ) && apify_api_data_model_name_exists($data['data_model_name']) ) {
		$errors->add( 'name_exists', __( '<strong>ERROR</strong>: This name is already registered, please choose another one.' ), array( 'form-field' => 'data_model_name' ) );
	}

	/* check desc */
	if ( empty($data['data_model_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'data_model_desc_en' ) );
	} 

	return $errors;
}

/**
 * 检验api model field POST数据
 */
function apify_validate_api_model_field($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['model_field_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'model_field_name' ) );
	} 

	/* check desc */
	if ( empty($data['model_field_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'model_field_desc_en' ) );
	} 

	/* check model_field_data_type_id */
	if ( empty($data['model_field_data_type_id']) ) {
		$errors->add( 'model_data_type_id', __( '<strong>ERROR</strong>: Please enter sample.' ), array( 'form-field' => 'model_field_data_type_id' ) );
	} 

	/* check data_model_id */
	if ( empty($data['data_model_id']) ) {
		$errors->add( 'empty_data_model_id', __( '<strong>ERROR</strong>: Please enter sample.' ), array( 'form-field' => 'data_model_id' ) );
	} 

	return $errors;
}

/**
 * 检验api errorcode group POST数据
 */
function apify_validate_api_errorcode_group($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['error_code_group_name']) ) {
		$errors->add( 'empty_name', __( '<strong>ERROR</strong>: Please enter name.' ), array( 'form-field' => 'error_code_group_name' ) );
	} elseif( ( strtolower($action) == 'insert' ) && apify_api_error_code_group_name_exists($data['error_code_group_name']) ) {
		$errors->add( 'name_exists', __( '<strong>ERROR</strong>: This name is already registered, please choose another one.' ), array( 'form-field' => 'error_code_group_name' ) );
	}

	/* check desc */
	if ( empty($data['error_code_group_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'error_code_group_desc_en' ) );
	} 

	return $errors;
}

/**
 * 检验api errorcode POST数据
 */
function apify_validate_api_errorcode($data, $action='insert') {
	$errors = new WP_Error();
	
	/* check name */
	if ( empty($data['error_code_key']) ) {
		$errors->add( 'empty_key', __( '<strong>ERROR</strong>: Please enter errorcode key.' ), array( 'form-field' => 'error_code_key' ) );
	} elseif( ( strtolower($action) == 'insert' ) && apify_api_error_code_key_exists($data['error_code_key']) ) {
		$errors->add( 'key_exists', __( '<strong>ERROR</strong>: This key is already registered, please choose another one.' ), array( 'form-field' => 'error_code_key' ) );
	} 

	/* check error_code_group_id */
	if ( empty($data['error_code_group_id']) || ($data['error_code_group_id'] == '-1') ) {
		$errors->add( 'empty_group_id', __( '<strong>ERROR</strong>: Please select group.' ), array( 'form-field' => 'error_code_group_id' ) );
	} 

	/* check desc */
	if ( empty($data['error_code_desc_en']) ) {
		$errors->add( 'empty_desc_en', __( '<strong>ERROR</strong>: Please enter description.' ), array( 'form-field' => 'error_code_desc_en' ) );
	} 	

	return $errors;
}


/******************************** frontend function  **************************************/

/**
 * 面包条路径逻辑
 */
function apify_get_breadcrums_list($params = array()) {
	$page_path = array();
		
	$main_page = trim($params['q']);
	$lang_code = (isset($params['lang']) && (strtolower($params['lang']) == 'en')) ? 'en' : 'zh';
	$group_title = 'group_title_'.$lang_code;
	$i18n_title_array = array(
						'docmt-center' => array('en'=>'Document Center', 'zh'=>'文档中心'),
						'api-docmt' => array('en'=>'API Document', 'zh'=>'API文档'),
					);

	if( !empty($main_page) ) {
		//HOME	
		$page_path[] = array( 'title'=>$i18n_title_array['docmt-center'][$lang_code], 'uri'=>'/api-group-list' );
		
		switch( $main_page ) {
			case APIFY_MAIN_PAGE_API_ITEM_LIST:
				//获取当前api类目详情
				$api_group_list = apifyDMSGetApiGroupList( array('visiable'=>1, 'status'=>1), array(intval($params['group_id'])) );
				$currentApiGroupObj = $api_group_list[0];

				$page_path[] = array( 'title'=>$i18n_title_array['api-docmt'][$lang_code], 'uri'=>'/api-group-list' );
				$page_path[] = array( 'title'=>$currentApiGroupObj->$group_title, 'uri'=>'#' );	
				break;
			case APIFY_MAIN_PAGE_API_ITEM:
				//获取当前api Item详情
				$api_data_item_list = apifyDMSGetApiItemList( array('visiable'=>1, 'status'=>1), array( intval($params['id']) ) );
				$currentApiItemObj = $api_data_item_list[0];
				//获取当前api所属类目详情
				$api_group_list = apifyDMSGetApiGroupList( array('visiable'=>1, 'status'=>1), array(intval($currentApiItemObj->item_group_id)) );
				$currentApiGroupObj = $api_group_list[0];

				$page_path[] = array( 'title'=>$i18n_title_array['api-docmt'][$lang_code], 'uri'=>'/api-group-list' );
				$page_path[] = array( 'title'=>$currentApiGroupObj->$group_title, 'uri'=>'/api-item-list?group_id='.intval($currentApiItemObj->item_group_id) );	
				$page_path[] = array( 'title'=>$currentApiItemObj->item_name, 'uri'=>'#' );	
				break;
			case APIFY_MAIN_PAGE_API_GROUP_LIST:
			case APIFY_MAIN_PAGE_API_DATA_MODEL:
			case APIFY_MAIN_PAGE_API_DATA_TYPE_LIST:
			case APIFY_MAIN_PAGE_API_ERRORCODE_LIST:
			case APIFY_MAIN_PAGE_API_UPDATE_HISTORY_LIST:
				$page_path[] = array( 'title'=>$i18n_title_array['api-docmt'][$lang_code], 'uri'=>'/api-group-list' );
				break;
			default:
				//do nothing
				break;
		}
	}
	
	return $page_path;

}
