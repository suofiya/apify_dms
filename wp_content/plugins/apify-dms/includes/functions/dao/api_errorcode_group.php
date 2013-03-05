<?php
/**
 * 数据库操作errorcode_group函数集
 *
 * @package    includes/functions
 * @subpackage dao
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */

/**
 * 根据filters筛选所有api errorcode group列表
 */
function apify_get_all_api_errorcode_group_list_by_filters( $filters=array(), $page_no=1, $page_size=20) {
	global $wpdb;
	
	$result = array();
	
	$cond = ' FROM '. TABLE_APIFY_API_ERROR_CODE_GROUP;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'error_code_group_id':
						$filter = "error_code_group_id='$v'";
						break;
					case 'error_code_group_name':
						$filter = "error_code_group_name like '%$v%'";
						break;
					case 'visiable':
						$filter = "visiable='$v'";
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

	$sql2 = 'SELECT count(*) FROM '. TABLE_APIFY_API_ERROR_CODE_GROUP . ' WHERE status=1';
	$valid_total = $wpdb->get_var( $wpdb->prepare( $sql2 ) );
	$result['valid_total'] = !empty($valid_total) ? intval($valid_total) : 0;
	$result['invalid_total'] = $total - $result['valid_total'];
	
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
 * 通过参数返回id为key，参数名对应值为value的数组，共下拉选择框使用
 */
function apify_get_api_errorcode_group_selection($filters, $value_key) {
	global $wpdb;
	
	$selection_array = array('-1'=>'-- None --');

	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_ERROR_CODE_GROUP;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'error_code_group_id':
						$filter = "error_code_group_id='$v'";
						break;
					case 'error_code_group_name':
						$filter = "error_code_group_name like '%$v%'";
						break;
					case 'visiable':
						$filter = "visiable='$v'";
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

	$list = $wpdb->get_results($sql. $cond);
	//	
	if( !empty($list) ) {
		foreach( $list as $item ) {
			$selection_array[$item->error_code_group_id] = $item->$value_key;
		}
	}

	return $selection_array;
}


/**
 * 验证error_code_group_name已经注册过
 */
function apify_api_error_code_group_name_exists($error_code_group_name) {
	global $wpdb;
	
	$sql = 'SELECT count(*) FROM '. TABLE_APIFY_API_ERROR_CODE_GROUP . ' WHERE error_code_group_name="' . $error_code_group_name . '"';

	$total = $wpdb->get_var( $wpdb->prepare( $sql ) );

	return !empty($total) ? true : false;
}


/**
 * 通过filters获取单个api errorcode group信息
 */
function apify_get_api_errorcode_group_by_filters($filters) {
	global $wpdb;
	
	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_ERROR_CODE_GROUP;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'error_code_group_id':
						$filter = "error_code_group_id='$v'";
						break;
					case 'error_code_group_name':
						$filter = "error_code_group_name like '%$v%'";
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
 * 编辑app errorcode group
 */
function apify_update_api_errorcode_group($data, $error_code_group_id) {
	global $wpdb;

	$upsets = array(
					'error_code_group_name' => $data['error_code_group_name'],
					'error_code_group_desc_en' => $data['error_code_group_desc_en'],
					'error_code_group_desc_zh' => $data['error_code_group_desc_zh'],
					'error_code_group_note' => $data['error_code_group_note'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$where = array( 'error_code_group_id' => intval($error_code_group_id) );

	$result = $wpdb->update( TABLE_APIFY_API_ERROR_CODE_GROUP, $upsets, $where );
	
	return $result;
}

/**
 * 新建api errorcode group
 */
function apify_insert_api_errorcode_group($data) {
	global $wpdb;

	$upsets = array(
					'error_code_group_name' => $data['error_code_group_name'],
					'error_code_group_desc_en' => $data['error_code_group_desc_en'],
					'error_code_group_desc_zh' => $data['error_code_group_desc_zh'],
					'error_code_group_note' => $data['error_code_group_note'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$wpdb->insert(TABLE_APIFY_API_ERROR_CODE_GROUP, $upsets);
	$error_code_group_id = $wpdb->insert_id;

	return $error_code_group_id;
}
