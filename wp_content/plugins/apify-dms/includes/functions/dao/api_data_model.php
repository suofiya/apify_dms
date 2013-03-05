<?php
/**
 * 数据库操作api data model函数集
 *
 * @package    includes/functions
 * @subpackage dao
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */


/**
 * 根据filters筛选所有api dataModel列表
 */
function apify_get_all_api_data_model_list_by_filters( $filters=array(), $page_no=1, $page_size=20) {
	global $wpdb;
	
	$result = array();
	
	$cond = ' FROM '. TABLE_APIFY_API_DATA_MODEL;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'data_model_id':
						$filter = "data_model_id='$v'";
						break;
					case 'data_model_name':
						$filter = "data_model_name like '%$v%'";
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

	$sql2 = 'SELECT count(*) FROM '. TABLE_APIFY_API_DATA_MODEL . ' WHERE status=1';
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
function apify_get_api_data_model_selection($filters, $value_key) {
	global $wpdb;
	
	$selection_array = array('-1'=>'-- None --');

	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_DATA_MODEL;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'data_model_id':
						$filter = "data_model_id='$v'";
						break;
					case 'data_model_name':
						$filter = "data_model_name like '%$v%'";
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
			$selection_array[$item->data_model_id] = $item->$value_key;
		}
	}

	return $selection_array;
}


/**
 * 验证data_model_name已经注册过
 */
function apify_api_data_model_name_exists($data_model_name) {
	global $wpdb;
	
	$sql = 'SELECT count(*) FROM '. TABLE_APIFY_API_DATA_MODEL . ' WHERE data_model_name="' . $data_model_name . '"';

	$total = $wpdb->get_var( $wpdb->prepare( $sql ) );

	return !empty($total) ? true : false;
}

/**
 * 通过filters获取单个api dataModel信息
 */
function apify_get_api_data_model_by_filters($filters) {
	global $wpdb;
	
	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_DATA_MODEL;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'data_model_id':
						$filter = "data_model_id='$v'";
						break;
					case 'data_model_name':
						$filter = "data_model_name like '%$v%'";
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
 * 编辑app datamodel
 */
function apify_update_api_data_model($data, $data_model_id) {
	global $wpdb;

	$upsets = array(
					'data_model_name' => $data['data_model_name'],
					'data_model_desc_en' => $data['data_model_desc_en'],
					'data_model_desc_zh' => $data['data_model_desc_zh'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$where = array( 'data_model_id' => intval($data_model_id) );

	$result = $wpdb->update( TABLE_APIFY_API_DATA_MODEL, $upsets, $where );
	
	return $result;
}

/**
 * 新建api dataModel
 */
function apify_insert_api_data_model($data) {
	global $wpdb;

	$upsets = array(
					'data_model_name' => $data['data_model_name'],
					'data_model_desc_en' => $data['data_model_desc_en'],
					'data_model_desc_zh' => $data['data_model_desc_zh'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$wpdb->insert(TABLE_APIFY_API_DATA_MODEL, $upsets);
	$data_model_id = $wpdb->insert_id;

	return $data_model_id;
}


/****************************  frontend functions  ****************************/

/**
 * 获取所有的api数据结构列表
 */
function apifyDMSGetApiDataModelList( $filters , $ids=array()) {
	global $wpdb;

	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_DATA_MODEL;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'data_model_id':
						$filter = "data_model_id='$v'";
						break;
					case 'data_model_name':
						$filter = "data_model_name like '%$v%'";
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

	//filter2
	if( !empty($ids) ) {
		$ids_str = implode(',', $ids);
		$cond .= " AND data_model_id IN ($ids_str)";
	}
	
	$list = $wpdb->get_results($sql.$cond);
	
	return $list;
}
