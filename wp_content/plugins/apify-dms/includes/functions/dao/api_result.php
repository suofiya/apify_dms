<?php
/**
 * 数据库操作api result函数集
 *
 * @package    includes/functions
 * @subpackage dao
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */


/**
 * 根据filters筛选所有api result列表
 */
function apify_get_all_api_result_list_by_filters( $filters=array(), $page_no=1, $page_size=20) {
	global $wpdb;
	
	$result = array();
	
	$cond = ' FROM '. TABLE_APIFY_API_RESULT;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'result_id':
						$filter = "result_id='$v'";
						break;
					case 'result_name':
						$filter = "result_name like '%$v%'";
						break;
					case 'result_data_type_id':
						$filter = "result_data_type_id='$v'";
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

	$sql2 = 'SELECT count(*) FROM '. TABLE_APIFY_API_RESULT . ' WHERE status=1';
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
 * 验证result_name已经注册过
 */
function apify_api_result_name_exists($result_name) {
	global $wpdb;
	
	$sql = 'SELECT count(*) FROM '. TABLE_APIFY_API_RESULT . ' WHERE result_name="' . $result_name . '"';

	$total = $wpdb->get_var( $wpdb->prepare( $sql ) );

	return !empty($total) ? true : false;
}


/**
 * 通过filters获取单个api result信息
 */
function apify_get_api_result_by_filters($filters) {
	global $wpdb;
	
	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_RESULT;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'result_id':
						$filter = "result_id='$v'";
						break;
					case 'result_name':
						$filter = "result_name like '%$v%'";
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
 * 编辑app model field
 */
function apify_update_api_result($data, $result_id) {
	global $wpdb;

	$upsets = array(
					'result_name' => $data['result_name'],
					'result_desc_en' => $data['result_desc_en'],
					'result_desc_zh' => $data['result_desc_zh'],
					'result_data_type_id' => $data['result_data_type_id'],
					'result_model_id' => (intval($data['result_model_id']) > 0 ) ? intval($data['result_model_id']) : 0,
					'item_id' => $data['item_id'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$where = array( 'result_id' => intval($result_id) );

	$result = $wpdb->update( TABLE_APIFY_API_RESULT, $upsets, $where );
	
	return $result;
}

/**
 * 新建api Model field
 */
function apify_insert_api_result($data) {
	global $wpdb;

	$upsets = array(
					'result_name' => $data['result_name'],
					'result_desc_en' => $data['result_desc_en'],
					'result_desc_zh' => $data['result_desc_zh'],
					'result_data_type_id' => $data['result_data_type_id'],
					'result_model_id' => (intval($data['result_model_id']) > 0 ) ? intval($data['result_model_id']) : 0,
					'item_id' => $data['item_id'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$wpdb->insert(TABLE_APIFY_API_RESULT, $upsets);
	$result_id = $wpdb->insert_id;

	return $result_id;
}


/****************************  frontend functions  ****************************/

/**
 * 获取所有的api对象字段列表
 */
function apifyDMSGetApiResultList( $filters , $ids=array()) {
	global $wpdb;

	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_RESULT;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'result_id':
						$filter = "result_id='$v'";
						break;
					case 'result_name':
						$filter = "result_name like '%$v%'";
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
		$cond .= " AND result_id IN ($ids_str)";
	}
	
	$list = $wpdb->get_results($sql.$cond);
	
	return $list;
}


/**
 * 获取api类目下所有api返回的数据对象id
 */
function apifyDMSGetApiResultModelIdsListByGroupId( $group_id ) {
	global $wpdb;
	
	$ids = array();

	$sql = 'SELECT DISTINCT ar.result_model_id FROM '. TABLE_APIFY_API_RESULT . ' as ar';
	$sql .= ' LEFT JOIN '. TABLE_APIFY_API_ITEM . ' as ai';
	$sql .= ' ON ar.item_id=ai.item_id';
	$sql .= ' WHERE ar.visiable=1 AND ar.status=1 AND ai.item_group_id='. intval($group_id);

	$list = $wpdb->get_results($sql);

	if( !empty($list) ) {
		foreach( $list as $v ) {
			if( intval($v->result_model_id) > 0 ) {
				$ids[] = intval($v->result_model_id);
			}
		}
	}
	
	return $ids;
}
