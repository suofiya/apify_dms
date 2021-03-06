<?php
/**
 * 数据库操作app client函数集
 *
 * @package    includes/functions
 * @subpackage dao
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */



/**
 * 根据filters筛选所有app client列表
 */
function apify_get_all_app_client_list_by_filters( $filters=array(), $page_no=1, $page_size=20) {
	global $wpdb;

	$result = array();
	
	$cond = ' FROM '. TABLE_APIFY_API_APP_CLIENT;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'app_client_id':
						$filter = "app_client_id='$v'";
						break;
					case 'app_client_name':
						$filter = "app_client_name like '%$v%'";
						break;
					case 'verified':
						$filter = "verified='$v'";
						break;		
					case 'status':
						$filter = "status='$v'";
						break;
					default:
						$filter = '';
						break;
			}
			if( !empty($filter) ) {
				$cond .= " AND $filter";
			}	
		}
	}
	
	//pagination
	$begin = ( $page_no > 1 ? ($page_no - 1) : 0 ) * $page_size;
	$size =  $page_size;
	
	//result
	$sql1 = 'SELECT count(*)' . $cond;
	$total = $wpdb->get_var( $wpdb->prepare($sql1) );
	$result['total'] = !empty($total) ? intval($total) : 0;

	$sql2 = 'SELECT count(*) FROM '. TABLE_APIFY_API_APP_CLIENT . ' WHERE verified=1';
	$verified_total = $wpdb->get_var( $wpdb->prepare( $sql2 ) );
	$result['verified_total'] = !empty($verified_total) ? intval($verified_total) : 0;
	$result['unverified_total'] = $total - $result['verified_total'];
	
	$sql3 = 'SELECT * ';
	$sql3 .= $cond;
	$sql3 .= ' ORDER BY last_modified DESC';
	$sql3 .= " Limit $begin, $size";
	
	$list = $wpdb->get_results($sql3);
	
	$result['list'] = $list;
	$result['page_no'] = $page_no;
	$result['page_size'] = $page_size;
	
	return $result;	
}


/**
 * 验证app_client_name已经注册过
 */
function apify_app_client_name_exists($app_client_name) {
	global $wpdb;
	
	$sql = 'SELECT count(*) FROM '. TABLE_APIFY_API_APP_CLIENT . ' WHERE app_client_name="' . $app_client_name . '"';

	$total = $wpdb->get_var( $wpdb->prepare( $sql ) );

	return !empty($total) ? true : false;
}

/**
 * 通过filters获取单个app client信息
 */
function apify_get_app_client_by_filters($filters) {
	global $wpdb;
	
	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_APP_CLIENT;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'app_client_id':
						$filter = "app_client_id='$v'";
						break;
					case 'app_admin_id':
						$filter = "app_admin_id='$v'";
						break;
					case 'app_client_key':
						$filter = "app_client_key='$v'";
						break;
					case 'app_client_secret':
						$filter = "app_client_secret='$v'";
						break;
					default:
						$filter = '';
						break;
			}
			if( !empty($filter) ) {
				$cond .= " AND $filter";
			}	
		}
	}
	//limit
	$limit = 'Limit 1';
	
	$list = $wpdb->get_results($sql. $cond. $limit);
	
	return $list[0];
}

/**
 * 编辑app client
 */
function apify_update_app_client($data, $app_client_id) {
	global $wpdb;

	$upsets = array(
					'app_client_name' => $data['app_client_name'],
					'app_client_desc_en' => $data['app_client_desc_en'],
					'app_client_desc_zh' => $data['app_client_desc_zh'],
					'app_client_key' => $data['app_client_key'],
					'app_client_secret' => $data['app_client_secret'],
					'app_admin_id' => $data['app_admin_id'],
					'verified' => $data['verified'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);
	$where = array( 'app_client_id' => intval($app_client_id) );

	$result = $wpdb->update( TABLE_APIFY_API_APP_CLIENT, $upsets, $where );
	
	return $result;
}

/**
 * 新建app client
 */
function apify_insert_app_client($data) {
	global $wpdb;

	$upsets = array(
					'app_client_name' => $data['app_client_name'],
					'app_client_desc_en' => $data['app_client_desc_en'],
					'app_client_desc_zh' => $data['app_client_desc_zh'],
					'app_client_key' => $data['app_client_key'],
					'app_client_secret' => $data['app_client_secret'],
					'app_admin_id' => $data['app_admin_id'],
					'verified' => $data['verified'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$wpdb->insert(TABLE_APIFY_API_APP_CLIENT, $upsets);
	$app_client_id = $wpdb->insert_id;

	return $app_client_id;
}
