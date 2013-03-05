<?php
/**
 *  基于wordpress搭建的API文档管理系统
 *
 *  卸载数据库
 */
global $wpdb; 
   
/*** delete option ***/
delete_option('apify_dms_db_version');

/*** drop tables ***/
$tables = array();
$tables[] = TABLE_APIFY_API_GROUP;
$tables[] = TABLE_APIFY_API_ITEM;
$tables[] = TABLE_APIFY_API_PARAMETER;
$tables[] = TABLE_APIFY_API_DATA_MODEL;
$tables[] = TABLE_APIFY_API_MODEL_FIELD;
$tables[] = TABLE_APIFY_API_ERROR_CODE_GROUP;
$tables[] = TABLE_APIFY_API_ERROR_CODE;
$tables[] = TABLE_APIFY_API_DATA_TYPE;
$tables[] = TABLE_APIFY_API_APP_CLIENT;
$tables[] = TABLE_APIFY_API_APP_ADMIN;

foreach ($tables as $t) {
	$sql = "DROP TABLE IF EXISTS {$t};";
	dbDelta( $sql );
}
