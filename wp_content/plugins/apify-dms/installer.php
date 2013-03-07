<?php
/**
 *  基于wordpress搭建的API文档管理系统
 *
 *  数据库和系统的初始化
 */
//*** Installer ***
global $wp_version, $wpdb;

/*** Define Wordpress Updater ***/
if ( version_compare( $wp_version, '3.0', '<' ) ) {
	require_once( ABSPATH . 'wp-admin/upgrade.php' );
} else {
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
}

/*** Drop tables for Dev & Test ***/
$sql_drop_tables = 'DROP TABLE IF EXISTS ' . TABLE_APIFY_API_GROUP . ',' ;
$sql_drop_tables .=  TABLE_APIFY_API_ITEM . ',';
$sql_drop_tables .=  TABLE_APIFY_API_PARAMETER . ',';
$sql_drop_tables .=  TABLE_APIFY_API_RESULT . ',';
$sql_drop_tables .=  TABLE_APIFY_API_DATA_MODEL . ',';
$sql_drop_tables .=  TABLE_APIFY_API_MODEL_FIELD . ',';
$sql_drop_tables .=  TABLE_APIFY_API_ERROR_CODE_GROUP . ',';
$sql_drop_tables .=  TABLE_APIFY_API_ERROR_CODE . ',';
$sql_drop_tables .=  TABLE_APIFY_API_DATA_TYPE . ',';
$sql_drop_tables .=  TABLE_APIFY_API_APP_CLIENT . ',';
$sql_drop_tables .=  TABLE_APIFY_API_APP_ADMIN . ';';
$wpdb->query($sql_drop_tables);

/*** Create tables SQL ****/
$sql[TABLE_APIFY_API_DATA_TYPE]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_DATA_TYPE . ' (
  `data_type_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api数据类型id",
  `data_type_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据类型名字",
  `data_type_type` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api数据类型类型: 1 basic, 9 model, 91 model list",
  `data_type_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据类型英文描述",
  `data_type_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据类型中文描述",
  `data_type_sample` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据类型示例",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api数据类型创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api数据类型更新时间",
  PRIMARY KEY  (`data_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';

$sql[TABLE_APIFY_API_APP_ADMIN]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_APP_ADMIN . ' (
  `app_admin_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api应用管理员id",
  `app_admin_email` VARCHAR(96) NOT NULL DEFAULT "" COMMENT "api应用管理员emai",
  `app_admin_password` VARCHAR(40) NOT NULL DEFAULT "" COMMENT "api应用管理员密码",
  `app_admin_name` VARCHAR(96) NOT NULL DEFAULT "" COMMENT "api应用管理员名字",
  `app_admin_gender` CHAR(1) NOT NULL DEFAULT "" COMMENT "api应用管理员性别: m male, f female",
  `app_admin_phone` VARCHAR(32) NOT NULL DEFAULT "" COMMENT "api应用管理员手机",  
  `verified` TINYINT(2) NOT NULL DEFAULT "0" COMMENT "是否认证通过: 1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api数据类型创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api数据类型更新时间",
  PRIMARY KEY  (`app_admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';

$sql[TABLE_APIFY_API_APP_CLIENT]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_APP_CLIENT . ' (
  `app_client_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api接入应用id",
  `app_client_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api接入应用名字",
  `app_client_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api接入应用英文描述",
  `app_client_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api接入应用中文描述",
  `app_client_key` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api接入应用key",
  `app_client_secret` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api接入应用secrect",
  `app_admin_id` INT(11) UNSIGNED NOT NULL COMMENT "api应用管理员id",
  `verified` TINYINT(2) NOT NULL DEFAULT "0" COMMENT "是否认证通过: 1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api接入应用创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api接入应用更新时间",
  PRIMARY KEY  (`app_client_id`),
  KEY `index_api_app_admin_id` (`app_admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';


$sql[TABLE_APIFY_API_GROUP]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_GROUP . ' (
  `group_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api所属组id",
  `group_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api所属组名字",
  `group_title_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api所属组英文标题",
  `group_title_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api所属组中文标题",
  `group_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api所属组英文描述",
  `group_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api所属组中文描述",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api组创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api组更新时间",
  PRIMARY KEY  (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;  ';

$sql[TABLE_APIFY_API_ITEM]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_ITEM . ' (
  `item_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api自增id",
  `item_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api名字",
  `item_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api功能英文描述",
  `item_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api功能中文描述",
  `item_note_en` TEXT NOT NULL DEFAULT "" COMMENT "api英文Note",
  `item_note_zh` TEXT NOT NULL DEFAULT "" COMMENT "api中文Note",
  `item_type` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api类型: 1 normal, 2 security",
  `item_group_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api所属组id",
  `item_response` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api返回结果,以/分隔,{,,}/{,,}",
  `item_response_example_json` TEXT NOT NULL DEFAULT "" COMMENT "api返回结果示例(json数据格式)",
  `item_response_example_xml` TEXT NOT NULL DEFAULT "" COMMENT "api返回结果示例(xml数据格式)",
  `item_response_example_json_text` LONGTEXT NOT NULL DEFAULT "" COMMENT "api返回结果(SyntaxHighlighter格式化过json式)",
  `item_model_ids` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api返回数据对象id列表, 以,分隔",
  `item_errorcode_ids` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码id列表, 以,分隔",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api最后一次更新时间",
  PRIMARY KEY  (`item_id`),
  KEY `index_api_item_group_id` (`item_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;  ';

$sql[TABLE_APIFY_API_PARAMETER]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_PARAMETER . ' (
  `parameter_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api参数id",
  `parameter_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api参数名字",
  `parameter_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api参数英文描述",
  `parameter_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api参数中文描述",
  `parameter_level` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api参数级别: 1 system, 2 api",
  `parameter_data_type_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api数据类型id",
  `parameter_required_mode` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 1 required, 1 optional, 91 specail required, 92 special optional,",
  `parameter_rule` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api参数规则",
  `parameter_minlength` INT(4) NOT NULL DEFAULT "0" COMMENT "api字符串型参数最小长度",
  `parameter_maxlength` INT(4) NOT NULL DEFAULT "0" COMMENT "api字符串型参数最大长度",
  `parameter_min` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数值型参数最小值",
  `parameter_max` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数值型参数最大值",
  `parameter_enum` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api参数可选值范围,以|分隔",
  `parameter_default_value` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api参数默认值",
  `parameter_sample` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api参数示例",
  `item_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api id",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api参数创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api参数更新时间",
  PRIMARY KEY  (`parameter_id`),
  KEY `index_api_parameter_data_type_id` (`parameter_data_type_id`),
  KEY `index_api_item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';

$sql[TABLE_APIFY_API_RESULT]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_RESULT . ' (
  `result_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "自增id",
  `result_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api返回结果名字",
  `result_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api返回结果英文描述",
  `result_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api返回结果中文描述",
  `result_data_type_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api数据类型id",
  `result_model_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api数据对象id",
  `item_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api id",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api返回结果创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api返回结果最后一次更新时间",
  PRIMARY KEY  (`result_id`),
  KEY `index_api_item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;  ';

$sql[TABLE_APIFY_API_DATA_MODEL]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_DATA_MODEL . ' (
  `data_model_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api数据结构id",
  `data_model_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据结构名字",
  `data_model_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据结构英文描述",
  `data_model_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据结构中文描述",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api数据结构创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api数据结构更新时间",
  PRIMARY KEY  (`data_model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';

$sql[TABLE_APIFY_API_MODEL_FIELD]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_MODEL_FIELD . ' (
  `model_field_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api数据字段id",
  `model_field_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据字段名字",
  `model_field_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据字段英文描述",
  `model_field_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据字段中文描述",
  `model_field_data_type_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api数据类型id",
  `model_field_model_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api数据对象id",
  `model_field_sample` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api数据字段示例",
  `data_model_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api数据结构字段所属数据对象",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api数据字段创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api数据字段更新时间",
  PRIMARY KEY  (`model_field_id`),
  KEY `index_api_model_field_data_type_id` (`model_field_data_type_id`),
  KEY `index_api_data_model_id` (`data_model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';

$sql[TABLE_APIFY_API_ERROR_CODE_GROUP]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_ERROR_CODE_GROUP . ' (
  `error_code_group_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api错误码组id",
  `error_code_group_name` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码组名字",
  `error_code_group_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码组英文描述",
  `error_code_group_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码组中文描述",
  `error_code_group_note` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码note",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api数据类型创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api数据类型更新时间",
  PRIMARY KEY  (`error_code_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';

$sql[TABLE_APIFY_API_ERROR_CODE]  = 'CREATE TABLE IF NOT EXISTS ' . TABLE_APIFY_API_ERROR_CODE . ' (
  `error_code_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "api错误码自增id",
  `error_code_key` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码key",
  `error_code_desc_en` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码英文描述",
  `error_code_desc_zh` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码中文描述",
  `error_code_group_id` INT(11) UNSIGNED NOT NULL DEFAULT "0" COMMENT "api错误码组id",
  `error_code_note` VARCHAR(255) NOT NULL DEFAULT "" COMMENT "api错误码note",
  `visiable` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "1 true, 0 false",
  `status` TINYINT(2) NOT NULL DEFAULT "1" COMMENT "api状态: 0 invalid, 1 valid, 99 deleted",
  `date_added` DATETIME NOT NULL DEFAULT "0001-01-01 00:00:00" COMMENT "api数据类型创建时间",
  `last_modified` DATETIME NOT NULL DEFAULT "1990-01-01 00:00:00" COMMENT "api数据类型更新时间",
  PRIMARY KEY  (`error_code_id`),
  KEY `index_api_error_code_key` (`error_code_key`),
  KEY `index_api_error_code_group_id` (`error_code_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	';

foreach ($sql as $q) {
	dbDelta( $q );
}

/*** Init data ****/
$current_datetime_str = date('Y-m-d H:i:s');

$data[] = "INSERT INTO ".TABLE_APIFY_API_APP_ADMIN. "(app_admin_email,app_admin_password,app_admin_name,app_admin_gender,app_admin_phone,verified,date_added,last_modified) values ('liutao@lightinthebox.com', '', 'Bruce Lau', 'm', '18701595943', 1, '{$current_datetime_str}', '{$current_datetime_str}');";

$data[] = "INSERT INTO ".TABLE_APIFY_API_APP_CLIENT. "(app_client_name,app_client_desc_en,app_client_desc_zh,app_client_key,app_client_secret,app_admin_id,verified,date_added,last_modified) values ('iphone', 'Vela iphone App', '兰亭iphone版应用', 'yyyyyy', 'yyyyyy', 1, 1, '{$current_datetime_str}', '{$current_datetime_str}');";

$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('String', 'string', '字符串', 'mc_products', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Number', 'number', '整数型数值', '1', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Float', 'float', '小数型数值', '12.99', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Boolean', 'boolean, true/false or 1/0', '布尔型值, true/false 或者 1/0', 'true', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Datetime', 'datetime, standard datetime format: yyyy-MM-dd HH:mm:ss', '日期时间字符串，标准时间格式:yyyy-MM-dd HH:mm:ss', '{$current_datetime_str}', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Field List', 'field list separated by comma', '以逗号分隔的field列表', 'item_id,item_name,price', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Json', 'json', '标准json型数据', '{\"sku_id\" : \"1\",\"sku_value_id\" : \"125\"}', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Number[]', 'a number list,seperated by comma', '以逗号分割的数字串列表', '1,2,3', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_type,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Model', 9, 'data model', '数据对象', 'Language', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('String[]', 'a string list,seperated by comma', '以逗号分割的字符串列表', 'new,freeshipping,fasedelivery', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Boolean[]', 'a boolean list,seperated by comma', '以逗号分割的boolean串列表', 'true,false,true', '{$current_datetime_str}', '{$current_datetime_str}');";
$data[] = "INSERT INTO ".TABLE_APIFY_API_DATA_TYPE. "(data_type_name,data_type_type,data_type_desc_en,data_type_desc_zh,data_type_sample,date_added,last_modified) values ('Model[]', 91, 'a data model list', '数据对象列表', '[\"langModel1\":Language, \"langModel2\":Language]', '{$current_datetime_str}', '{$current_datetime_str}');";

foreach ($data as $q) {
	dbDelta( $q );
}


//
function init_apify_dms_pages($pages){
	$currTime =date("Y-m-d H:i:s");
	foreach ($pages as $uri=>$param){
		$page = get_page_by_path($uri);
		if (!empty($page)){
			if ($page->post_status == 'trash'){
				wp_untrash_post($page->ID);
			}
		}else{	
			$post = array(
			  //'ID' => [ <post id> ] //Are you updating an existing post?
			  'menu_order' => '0', //[ <order> ] //If new post is a page, sets the order should it appear in the tabs.
			  //'page_template' => '', //Sets the template for the page.
			  'comment_status' => 'closed',//[ 'closed' | 'open' ] // 'closed' means no comments.
			  'ping_status' => 'closed', //Ping status?
			  'post_author' => 1, //The user ID number of the author.
			  //'post_category' => [ array(<category id>, <...>) ] //Add some categories.
			  'post_content' => $param['content'], //The full text of the post.
			  'post_date' => $currTime, //The time post was made.
			  'post_date_gmt' => $currTime, //The time post was made, in GMT.
			  'post_excerpt' => '',//For all your post excerpt needs.
			  'post_name' => $uri, // The name (slug) for your post
			  'post_parent' => 0, //Sets the parent of the new post.
			  'post_password' => '', //password for post?
			  'post_status' => 'publish', //[ 'draft' | 'publish' | 'pending' ] //Set the status of the new post.
			  'post_title' => $param['title'], //The title of your post.
			  'post_type' => 'page' , //[ 'post' | 'page' ] //Sometimes you want to post a page.
			  'tags_input' => '', //[ '<tag>, <tag>, <...>' ] //For tags.
			  'to_ping' => '', // //?
			  'pinged' => '',
			  'post_content_filtered'=>'',
			  'post_mime_type'=>'',
			  'comment_count'=>0,
			);  
			// Insert the post into the database
			wp_insert_post( $post );
		} //end else
	} //end foreach
} //end


/*** Init Front Pages ***/
init_apify_dms_pages($frontend_pages);


$db_options = array(
		'apify_dms_db_version' => APIFY_DMS_DB_VERSION,
		'apify_dms_api_tool_url' => APIFY_DMS_API_TOOL_URL,
);

function init_apify_dms_options( $options ) {
	if( is_array($option) && !empty($options) ) {
		foreach( $options as $k=>$v ) {
			if( !empty($k) ) {
				$installed_v = get_option( $k );
				if( empty($installed_v) ) {
					add_option( $k, $v );
				} elseif( $installed_v != $v ) {
					update_option( $k, $v );
				} else {
					//do nothing
					echo 'Apify_dms [ERROR] : init_apify_dms_options error, enter else case!!!';
				}
			}
		}
	}	
}

/*** Init Options ***/
init_apify_dms_options($db_options);

//***** End Installer *****
