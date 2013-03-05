<?php
/**
 * 数据库操作api parameter函数集
 *
 * @package    includes/functions
 * @subpackage dao
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */


/**
 * 根据filters筛选所有api parameter列表
 */
function apify_get_all_api_parameter_list_by_filters( $filters=array(), $page_no=1, $page_size=20) {
	global $wpdb;
	
	$result = array();
	
	$cond = ' FROM '. TABLE_APIFY_API_PARAMETER;
	$cond .= ' WHERE parameter_level=2 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'parameter_id':
						$filter = "parameter_id='$v'";
						break;
					case 'parameter_name':
						$filter = "parameter_name like '%$v%'";
						break;
					case 'parameter_required_mode':
						$filter = "parameter_required_mode='$v'";
						break;
					case 'item_id':
						$filter = "item_id='$v'";
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

	$sql2 = 'SELECT count(*) FROM '. TABLE_APIFY_API_PARAMETER . ' WHERE status=1';
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
 * 通过filters获取单个api parameter信息
 */
function apify_get_api_parameter_by_filters($filters) {
	global $wpdb;
	
	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_PARAMETER;
	$cond .= ' WHERE parameter_level=2 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'parameter_id':
						$filter = "parameter_id='$v'";
						break;
					case 'parameter_name':
						$filter = "parameter_name like '%$v%'";
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
 * 编辑app parameter
 */
function apify_update_api_parameter($data, $parameter_id) {
	global $wpdb;

	$upsets = array(
					'parameter_name' => $data['parameter_name'],
					'parameter_desc_en' => $data['parameter_desc_en'],
					'parameter_desc_zh' => $data['parameter_desc_zh'],
					'parameter_level' => 2,
					'parameter_data_type_id' => $data['parameter_data_type_id'],
					'parameter_required_mode' => $data['parameter_required_mode'],
					'parameter_rule' => $data['parameter_rule'],
					'parameter_minlength' => $data['parameter_minlength'],
					'parameter_maxlength' => $data['parameter_maxlength'],
					'parameter_min' => $data['parameter_min'],
					'parameter_max' => $data['parameter_max'],
					'parameter_enum' => $data['parameter_enum'],
					'parameter_default_value' => $data['parameter_default_value'],
					'parameter_sample' => $data['parameter_sample'],
					'item_id' => $data['item_id'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$where = array( 'parameter_id' => intval($parameter_id) );

	$result = $wpdb->update( TABLE_APIFY_API_PARAMETER, $upsets, $where );
	
	return $result;
}

/**
 * 新建api parameter
 */
function apify_insert_api_parameter($data) {
	global $wpdb;

	$upsets = array(
					'parameter_name' => $data['parameter_name'],
					'parameter_desc_en' => $data['parameter_desc_en'],
					'parameter_desc_zh' => $data['parameter_desc_zh'],
					'parameter_level' => 2,
					'parameter_data_type_id' => $data['parameter_data_type_id'],
					'parameter_required_mode' => $data['parameter_required_mode'],
					'parameter_rule' => $data['parameter_rule'],
					'parameter_minlength' => $data['parameter_minlength'],
					'parameter_maxlength' => $data['parameter_maxlength'],
					'parameter_min' => $data['parameter_min'],
					'parameter_max' => $data['parameter_max'],
					'parameter_enum' => $data['parameter_enum'],
					'parameter_default_value' => $data['parameter_default_value'],
					'parameter_sample' => $data['parameter_sample'],
					'item_id' => $data['item_id'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$wpdb->insert(TABLE_APIFY_API_PARAMETER, $upsets);
	$parameter_id = $wpdb->insert_id;

	return $parameter_id;
}


/****************************  frontend functions  ****************************/

/**
 * 获取所有的api级参数列表
 */
function apifyDMSGetApiParameterList( $filters , $ids=array()) {
	global $wpdb;

	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_PARAMETER;
	$cond .= ' WHERE parameter_level=2 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'parameter_id':
						$filter = "parameter_id='$v'";
						break;
					case 'parameter_name':
						$filter = "parameter_name like '%$v%'";
						break;
					case 'parameter_required_mode':
						$filter = "parameter_required_mode='$v'";
						break;
					case 'item_id':
						$filter = "item_id='$v'";
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
		$cond .= " AND parameter_id IN ($ids_str)";
	}
	
	$list = $wpdb->get_results($sql.$cond);
	
	return $list;
}
