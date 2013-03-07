<?php
/**
 * 数据库操作api更新历史函数集
 *
 * @package    includes/functions
 * @subpackage dao
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */


/**
 * 根据filters筛选所有api update列表
 */
function apify_get_all_api_update_history_list_by_filters( $filters=array(), $page_no=1, $page_size=20) {
	global $wpdb;
	
	$result = array();
	
	$cond = ' FROM '. TABLE_POST;
	$cond .= ' WHERE post_type="post" AND post_status like "publish%" ';
	//filter
	if( !empty($filters) ) {
		foreach( $filters as $k=>$v ) {
			$filter = '';
			switch($k) {
					case 'ID':
						$filter = "ID='$v'";
						break;
					case 'post_title':
						$filter = 'post_title like "'.$v.'%"';
						break;
					case 'result_data_type_id':
						$filter = "result_data_type_id='$v'";
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
	$sql1 = 'SELECT count(*)';
	$sql1 .= $cond;
	$total = $wpdb->get_var( $wpdb->prepare($sql1) );
	$result['total'] = !empty($total) ? intval($total) : 0;
	
	$sql2 = 'SELECT * ';
	$sql2 .= $cond;
	$sql2 .= ' ORDER BY post_modified DESC';
	$sql2 .= " Limit $begin, $size";
	
	$list = $wpdb->get_results($sql2);
	$result['list'] = $list;
	$result['page_no'] = $page_no;
	$result['page_size'] = $page_size;
	
	return $result;	
}

?>
