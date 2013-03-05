<?php
/**
 * 数据库操作api model field函数集
 *
 * @package    includes/functions
 * @subpackage dao
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */


/**
 * 根据filters筛选所有api Model field列表
 */
function apify_get_all_api_model_field_list_by_filters( $filters=array(), $page_no=1, $page_size=20) {
	global $wpdb;
	
	$result = array();
	
	$cond = ' FROM '. TABLE_APIFY_API_MODEL_FIELD;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'model_field_id':
						$filter = "model_field_id='$v'";
						break;
					case 'model_field_name':
						$filter = "model_field_name like '%$v%'";
						break;
					case 'data_model_id':
						$filter = "data_model_id='$v'";
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

	$sql2 = 'SELECT count(*) FROM '. TABLE_APIFY_API_MODEL_FIELD . ' WHERE status=1';
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
 * 验证model_field_name已经注册过
 */
function apify_api_model_field_name_exists($model_field_name) {
	global $wpdb;
	
	$sql = 'SELECT count(*) FROM '. TABLE_APIFY_API_MODEL_FIELD . ' WHERE model_field_name="' . $model_field_name . '"';

	$total = $wpdb->get_var( $wpdb->prepare( $sql ) );

	return !empty($total) ? true : false;
}


/**
 * 通过filters获取单个api Model Field信息
 */
function apify_get_api_model_field_by_filters($filters) {
	global $wpdb;
	
	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_MODEL_FIELD;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'model_field_id':
						$filter = "model_field_id='$v'";
						break;
					case 'data_model_name':
						$filter = "model_field_name like '%$v%'";
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
function apify_update_api_model_field($data, $model_field_id) {
	global $wpdb;

	$upsets = array(
					'model_field_name' => $data['model_field_name'],
					'model_field_desc_en' => $data['model_field_desc_en'],
					'model_field_desc_zh' => $data['model_field_desc_zh'],
					'model_field_data_type_id' => $data['model_field_data_type_id'],
					'model_field_model_id' => (intval($data['model_field_model_id']) > 0 ) ? intval($data['model_field_model_id']) : 0,
					'model_field_sample' => $data['model_field_sample'],
					'data_model_id' => $data['data_model_id'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$where = array( 'model_field_id' => intval($model_field_id) );

	$result = $wpdb->update( TABLE_APIFY_API_MODEL_FIELD, $upsets, $where );
	
	return $result;
}

/**
 * 新建api Model field
 */
function apify_insert_api_model_field($data) {
	global $wpdb;

	$upsets = array(
					'model_field_name' => $data['model_field_name'],
					'model_field_desc_en' => $data['model_field_desc_en'],
					'model_field_desc_zh' => $data['model_field_desc_zh'],
					'model_field_data_type_id' => $data['model_field_data_type_id'],
					'model_field_model_id' => (intval($data['model_field_model_id']) > 0 ) ? intval($data['model_field_model_id']) : 0,
					'model_field_sample' => $data['model_field_sample'],
					'data_model_id' => $data['data_model_id'],
					'visiable' => $data['visiable'],
					'status' => $data['status'],
					'date_added' => date('Y-m-d H:i:s'),
					'last_modified' => date('Y-m-d H:i:s'),
				);

	$wpdb->insert(TABLE_APIFY_API_MODEL_FIELD, $upsets);
	$model_field_id = $wpdb->insert_id;

	return $model_field_id;
}


/****************************  frontend functions  ****************************/

/**
 * 获取所有的api对象字段列表
 */
function apifyDMSGetApiModelFieldList( $filters , $ids=array()) {
	global $wpdb;

	$sql = 'SELECT *';
	$cond = ' FROM '. TABLE_APIFY_API_MODEL_FIELD;
	$cond .= ' WHERE 1=1 ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'model_field_id':
						$filter = "model_field_id='$v'";
						break;
					case 'model_field_name':
						$filter = "model_field_name like '%$v%'";
						break;
					case 'data_model_id':
						$filter = "data_model_id='$v'";
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
		$cond .= " AND model_field_id IN ($ids_str)";
	}
	
	$list = $wpdb->get_results($sql.$cond);
	
	return $list;
}
