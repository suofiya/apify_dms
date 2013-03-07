<?php  
/*
Plugin Name: Apify Document Manage System 
Plugin URI: https://github.com/suofiya/apify_dms  
Description: Display api document on frontend，Manage api document on backend by administrator 
Version: 1.0.0
Author: BruceLau liutao@lightinthebox.com
Author URI: http://www.lightinthebox.com/  
License: GPL  
*/ 
?>
<?php
/**
 * 全局变量
 */
global $wpdb;

/**
 * 全局常量设置
 */
define( 'APIFY_DMS_MANAGE_PRIVILEGE', 'edit_pages' ); //The minimum privilege required to manage plugin

/**
 * option常量设置
 */
define( 'APIFY_DMS_DB_VERSION', '1.0.0' );
define( 'APIFY_DMS_API_TOOL_URL', 'http://sandbox-api.lightinthebox.com/api/tools/api_tool.php' );

/**
 * 数据表定义
 */
//define( 'TABLE_POST', $wpdb->prefix . 'posts' );
define( 'APIFY_DATABASE_PREFIX', $wpdb->prefix . 'vela_' );
define( 'TABLE_APIFY_API_GROUP', APIFY_DATABASE_PREFIX . 'api_group' );
define( 'TABLE_APIFY_API_ITEM', APIFY_DATABASE_PREFIX . 'api_item' );
define( 'TABLE_APIFY_API_PARAMETER', APIFY_DATABASE_PREFIX . 'api_parameter' );
define( 'TABLE_APIFY_API_RESULT', APIFY_DATABASE_PREFIX . 'api_result' );
define( 'TABLE_APIFY_API_DATA_MODEL', APIFY_DATABASE_PREFIX . 'api_data_model' );
define( 'TABLE_APIFY_API_MODEL_FIELD', APIFY_DATABASE_PREFIX . 'api_model_field' );
define( 'TABLE_APIFY_API_ERROR_CODE_GROUP', APIFY_DATABASE_PREFIX . 'api_error_code_group' );
define( 'TABLE_APIFY_API_ERROR_CODE', APIFY_DATABASE_PREFIX . 'api_error_code' );
define( 'TABLE_APIFY_API_DATA_TYPE', APIFY_DATABASE_PREFIX . 'api_data_type' );
define( 'TABLE_APIFY_API_APP_CLIENT', APIFY_DATABASE_PREFIX . 'api_app_client' );
define( 'TABLE_APIFY_API_APP_ADMIN', APIFY_DATABASE_PREFIX . 'api_app_admin' );

/**
 * 前端页面main_page定义
 */
define( 'APIFY_MAIN_PAGE_API_UPDATE_HISTORY_LIST', 'api-update-history-list' );
define( 'APIFY_MAIN_PAGE_API_GROUP_LIST', 'api-group-list' );
define( 'APIFY_MAIN_PAGE_API_ITEM_LIST', 'api-item-list' );
define( 'APIFY_MAIN_PAGE_API_ITEM', 'api-item' );
define( 'APIFY_MAIN_PAGE_API_DATA_MODEL', 'api-data-model' );
define( 'APIFY_MAIN_PAGE_API_DATA_TYPE_LIST', 'api-data-type-list' );
define( 'APIFY_MAIN_PAGE_API_ERRORCODE_LIST', 'api-errorcode-list' );


/**
 * 前端页面定义(shortcode机制)
 */
$frontend_pages = array(			
			'api-update-history-list'=>array('title'=>'API文档更新历史', 'shortcode_tag'=>'displayApiUpdateHistory', 'content'=>'[displaySideNavigator onlyshowglobalnavigator="true"][displayApiUpdateHistory]'),
			'api-group-list'=>array('title'=>'API类目列表', 'shortcode_tag'=>'displayApiGroupList', 'content'=>'[displaySideNavigator onlyshowglobalnavigator="true"][displayApiGroupList]'),
			'api-item-list'=>array('title'=>'API列表', 'shortcode_tag'=>'displayApiItemList', 'content'=>'[displaySideNavigator pagetype="api-item-list"][displayApiItemList]'),
			'api-item'=>array('title'=>'API项目', 'shortcode_tag'=>'displayApiItem', 'content'=>'[displaySideNavigator pagetype="api-item"][displayApiItem]'),
			'api-data-model'=>array('title'=>'API数据对象', 'shortcode_tag'=>'displayApiDataModel', 'content'=>'[displaySideNavigator onlyshowglobalnavigator="true"][displayApiDataModel]'),
			'api-data-type-list'=>array('title'=>'API数据类型列表', 'shortcode_tag'=>'displayApiDataTypeList', 'content'=>'[displaySideNavigator onlyshowglobalnavigator="true"][displayApiDataTypeList]'),
			'api-errorcode-list'=>array('title'=>'API错误码列表', 'shortcode_tag'=>'displayApiErrorCodeList', 'content'=>'[displaySideNavigator onlyshowglobalnavigator="true"][displayApiErrorCodeList]'),
);
$extra_widgets = array(
			array('shortcode_tag'=>'displaySideNavigator', 'content'=>'[displaySideNavigator]'),
);

/* 注册激活插件时要调用的函数 */ 
register_activation_hook( __FILE__, 'apify_dms_install' );

/* 注册停用插件时要调用的函数 */ 
register_uninstall_hook(__FILE__, 'apify_dms_uninstall');


/**
 * Install
 */
function apify_dms_install () {
	require_once( dirname( __FILE__ ) . '/installer.php' );
}
/**
 * uninstall
 */
function apify_dms_uninstall(){
    require_once( dirname( __FILE__ ) . '/uninstaller.php' );
}


/**
 * 添加apify dms后台管理菜单 
 */
if( is_admin() ) {
	add_action('admin_menu', 'apify_dms_admin_menu');
}

/**
 * 添加apify dms后台ajax
 */
if( is_admin() ) {
	//client编辑页才注册
	if( isset($_GET['page']) && (trim($_GET['page']) == 'add-app-client') ) {
		wp_enqueue_script('app_manager', plugins_url('/js/app_manager.js', __FILE__), array('jquery'));
		add_action('wp_ajax_generate_app_key_secret_action', 'apify_ajax_generate_app_key_secret');
	}
}

/**
 * 添加apify dms前台页面shortcode
 */
apify_dms_add_shortcode( $frontend_pages, $extra_widgets );


/**
 * 生成菜单
 */
function apify_dms_admin_menu() {
	//app client top menu
	add_menu_page( __('App Client Page'), __('App Client'), APIFY_DMS_MANAGE_PRIVILEGE, 'app-client', 'view_app_client', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'app-client', __('View App Client Page'), __('View App Client'), APIFY_DMS_MANAGE_PRIVILEGE, 'app-client', 'view_app_client' );
	add_submenu_page( 'app-client', __('Add App Client Page'), __('Add App Client'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-app-client', 'add_app_client' );

	//app admin top menu
	add_menu_page( __('App Admin Page'), __('App Admin'), APIFY_DMS_MANAGE_PRIVILEGE, 'app-admin', 'view_app_admin', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'app-admin', __('View App Admin Page'), __('View App Admin'), APIFY_DMS_MANAGE_PRIVILEGE, 'app-admin', 'view_app_admin' );
	add_submenu_page( 'app-admin', __('Add App Admin Page'), __('Add App Admin'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-app-admin', 'add_app_admin' );

	//api group menu
	add_menu_page( __('Api Group Page'), __('Api Group'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-group', 'view_api_group', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-group', __('View Api Group Page'), __('View Api Group'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-group', 'view_api_group' );
	add_submenu_page( 'api-group', __('Add Api Group Page'), __('Add Api Group'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-group', 'add_api_group' );

	//api item menu
	add_menu_page( __('Api Item Page'), __('Api Item'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-item', 'view_api_item', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-item', __('View Api Item Page'), __('View Api Item'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-item', 'view_api_item' );
	add_submenu_page( 'api-item', __('Add Api Item Page'), __('Add Api Item'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-item', 'add_api_item' );

	//sys parameter menu
	add_menu_page( __('System Parameter Page'), __('System Parameter'), APIFY_DMS_MANAGE_PRIVILEGE, 'sys-parameter', 'view_sys_parameter', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'sys-parameter', __('View System Parameter Page'), __('View System Parameter'), APIFY_DMS_MANAGE_PRIVILEGE, 'sys-parameter', 'view_sys_parameter' );
	add_submenu_page( 'sys-parameter', __('Add System Parameter Page'), __('Add System Parameter'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-sys-parameter', 'add_sys_parameter' );
	
	//api parameter menu
	add_menu_page( __('Api Parameter Page'), __('Api Parameter'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-parameter', 'view_api_parameter', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-parameter', __('View Api Parameter Page'), __('View Api Parameter'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-parameter', 'view_api_parameter' );
	add_submenu_page( 'api-parameter', __('Add Api Parameter Page'), __('Add Api Parameter'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-parameter', 'add_api_parameter' );

	//api result menu
	add_menu_page( __('Api Result Page'), __('Api Result'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-result', 'view_api_result', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-result', __('View Api Result Page'), __('View Api Result'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-result', 'view_api_result' );
	add_submenu_page( 'api-result', __('Add Api Result Page'), __('Add Api Result'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-result', 'add_api_result' );

	//api datamodel menu
	add_menu_page( __('Api DataModel Page'), __('Api DataModel'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-data-model', 'view_api_data_model', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-data-model', __('View Api DataModel Page'), __('View Api DataModel'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-data-model', 'view_api_data_model' );
	add_submenu_page( 'api-data-model', __('Add Api DataModel Page'), __('Add Api DataModel'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-data-model', 'add_api_data_model' );

	//api modelfield menu
	add_menu_page( __('Api ModelField Page'), __('Api ModelField'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-model-field', 'view_api_model_field', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-model-field', __('View Api ModelField Page'), __('View Api ModelField'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-model-field', 'view_api_model_field' );
	add_submenu_page( 'api-model-field', __('Add Api ModelField Page'), __('Add Api ModelField'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-model-field', 'add_api_model_field' );

	//api datatype menu
	add_menu_page( __('Api DataType Page'), __('Api DataType'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-data-type', 'view_api_data_type', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-data-type', __('View Api DataType Page'), __('View Api DataType'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-data-type', 'view_api_data_type' );
	add_submenu_page( 'api-data-type', __('Add Api DataType Page'), __('Add Api DataType'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-data-type', 'add_api_data_type' );

	//api errorcode group menu
	add_menu_page( __('Api ErrorCode Group Page'), __('Api ErrorCode Group'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-errorcode-group', 'view_api_errorcode_group', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-errorcode-group', __('View Api ErrorCode Group Page'), __('View Api ErrorCode Group'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-errorcode-group', 'view_api_errorcode_group' );
	add_submenu_page( 'api-errorcode-group', __('Add Api ErrorCode Group Page'), __('Add Api ErrorCode Group'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-errorcode-group', 'add_api_errorcode_group' );

	//api errorcode menu
	add_menu_page( __('Api ErrorCode Page'), __('Api ErrorCode'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-errorcode', 'view_api_errorcode', apify_dms_get_plugin_dir( 'go' ) . '/images/icons/menu-icon.png' );
	add_submenu_page( 'api-errorcode', __('View Api ErrorCode Page'), __('View Api ErrorCode'), APIFY_DMS_MANAGE_PRIVILEGE, 'api-errorcode', 'view_api_errorcode' );
	add_submenu_page( 'api-errorcode', __('Add Api ErrorCode Page'), __('Add Api ErrorCode'), APIFY_DMS_MANAGE_PRIVILEGE, 'add-api-errorcode', 'add_api_errorcode' );
}


/**
 * 添加shortcode函数
 */
function apify_dms_add_shortcode( $pages, $extra_widgets ) {
	//pages
	if( is_array($pages) && !empty($pages) ) {
		foreach( $pages as $uri=>$param ) {
			$shortcode_tag = $param['shortcode_tag'];
			$func_name = $shortcode_tag.'Handler';
			if( function_exists( $func_name ) ) {
				add_shortcode( $shortcode_tag, $func_name );
			} else {
				echo '[apify_dms] Error: undefined shortcode function, function_name='.$func_name;
			}
		}
	}
	//extra
	if( is_array($extra_widgets) && !empty($extra_widgets) ) {
		foreach( $extra_widgets as $param ) {
			$shortcode_tag = $param['shortcode_tag'];
			$func_name = $shortcode_tag.'Handler';
			if( function_exists( $func_name ) ) {
				add_shortcode( $shortcode_tag, $func_name );
			} else {
				echo '[apify_dms] Error: undefined shortcode function, function_name='.$func_name;
			}
		}
	}
}


/**
 * 加载function文件
 */
// include in the functions
include_once( 'includes/functions/general.php' );
//Helper Functions
include_once( 'includes/functions/helper.php' );
//Common utils Functions
include_once( 'includes/functions/utils.php' );
//Business Logic Functions
include_once( 'includes/functions/manager.php' );
//Data Accesse Functions
include_once( 'includes/functions/dao/app_admin.php' );
include_once( 'includes/functions/dao/app_client.php' );
include_once( 'includes/functions/dao/api_data_type.php' );
include_once( 'includes/functions/dao/api_group.php' );
include_once( 'includes/functions/dao/api_item.php' );
include_once( 'includes/functions/dao/sys_parameter.php' );
include_once( 'includes/functions/dao/api_parameter.php' );
include_once( 'includes/functions/dao/api_result.php' );
include_once( 'includes/functions/dao/api_data_model.php' );
include_once( 'includes/functions/dao/api_model_field.php' );
include_once( 'includes/functions/dao/api_errorcode_group.php' );
include_once( 'includes/functions/dao/api_errorcode.php' );
include_once( 'includes/functions/dao/api_update_history.php' );
//Ajax Functions
include_once( 'includes/functions/ajax.php' );


/**
 * apify dms管理后台function页面映射配置
 */
function view_app_client() {
	include( 'includes/pages/admin/view_app_client.php' );
}
function add_app_client() {
	include( 'includes/pages/admin/add_app_client.php' );
}
function view_app_admin() {
	include( 'includes/pages/admin/view_app_admin.php' );
}
function add_app_admin() {
	include( 'includes/pages/admin/add_app_admin.php' );
}
function view_api_group() {
	include( 'includes/pages/admin/view_api_group.php' );
}
function add_api_group() {
	include( 'includes/pages/admin/add_api_group.php' );
}
function view_api_item() {
	include( 'includes/pages/admin/view_api_item.php' );
}
function add_api_item() {
	include( 'includes/pages/admin/add_api_item.php' );
}
function view_sys_parameter() {
	include( 'includes/pages/admin/view_sys_parameter.php' );
}
function add_sys_parameter() {
	include( 'includes/pages/admin/add_sys_parameter.php' );
}
function view_api_parameter() {
	include( 'includes/pages/admin/view_api_parameter.php' );
}
function add_api_parameter() {
	include( 'includes/pages/admin/add_api_parameter.php' );
}
function view_api_result() {
	include( 'includes/pages/admin/view_api_result.php' );
}
function add_api_result() {
	include( 'includes/pages/admin/add_api_result.php' );
}
function view_api_data_model() {
	include( 'includes/pages/admin/view_api_data_model.php' );
}
function add_api_data_model() {
	include( 'includes/pages/admin/add_api_data_model.php' );
}
function view_api_model_field() {
	include( 'includes/pages/admin/view_api_model_field.php' );
}
function add_api_model_field() {
	include( 'includes/pages/admin/add_api_model_field.php' );
}
function view_api_data_type() {
	include( 'includes/pages/admin/view_api_data_type.php' );
}
function add_api_data_type() {
	include( 'includes/pages/admin/add_api_data_type.php' );
}
function view_api_errorcode_group() {
	include( 'includes/pages/admin/view_api_errorcode_group.php' );
}
function add_api_errorcode_group() {
	include( 'includes/pages/admin/add_api_errorcode_group.php' );
}
function view_api_errorcode() {
	include( 'includes/pages/admin/view_api_errorcode.php' );
}
function add_api_errorcode() {
	include( 'includes/pages/admin/add_api_errorcode.php' );
}

/**
 * apify dms显示前台function页面映射配置
 */
function displaySideNavigatorHandler($shortcode_params) {
	global $onlyshowglobalnavigator,$pagetype;
	
	ob_start();
	extract( shortcode_atts( array (
										'onlyshowglobalnavigator' => false, //只显示全局导航区块(文档系统功能导航)
										'pagetype'   => 'index', //页面类型
    		 						),
    		 				$shortcode_params ) 
			);
	include( 'includes/pages/frontend/display_side_navigator.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function displayApiUpdateHistoryHandler() {
	ob_start();
	include( 'includes/pages/frontend/display_api_update_history_list.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function displayApiGroupListHandler() {
	ob_start();
	include( 'includes/pages/frontend/display_api_group_list.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function displayApiItemListHandler() {
	ob_start();
	include( 'includes/pages/frontend/display_api_item_list.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function displayApiItemHandler() {
	ob_start();
	include( 'includes/pages/frontend/display_api_item.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function displayApiDataModelHandler() {
	ob_start();
	include( 'includes/pages/frontend/display_api_data_model.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function displayApiDataTypeListHandler() {
	ob_start();
	include( 'includes/pages/frontend/display_api_data_type_list.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}
function displayApiErrorCodeListHandler() {
	ob_start();
	include( 'includes/pages/frontend/display_api_errorcode_list.php' );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}


/**
 * Return path to plugin directory (url/path)
 */
function apify_dms_get_plugin_dir( $type ) {
	if( !defined( 'WP_CONTENT_URL' ) )
		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if( !defined('WP_CONTENT_DIR') )
		define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if( $type == 'path' ) {
		return WP_CONTENT_DIR . '/plugins/' . plugin_basename( dirname( __FILE__ ) ); 
	} else { 
		return WP_CONTENT_URL . '/plugins/' . plugin_basename( dirname( __FILE__ ) ); 
	}
}

/**
 * 添加apify dms后台Js
 */
if( is_admin() ) {
	//Type和Model切换页面才注册
	if( isset($_GET['page']) && in_array(trim($_GET['page']), array('add-api-result', 'add-api-model-field')) ) {
		$api_data_type_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_type');
		wp_enqueue_script('type_model_exchange', plugins_url('/js/type_model_exchange.js', __FILE__), array('jquery'));
		wp_localize_script('type_model_exchange', 'dataTypeTypeList', $api_data_type_type_selection_array);
	}
}

?>
