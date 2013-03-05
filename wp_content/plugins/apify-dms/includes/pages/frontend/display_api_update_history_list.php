<?php
/**
 * 前台显示api更新历史记录
 *
 * 注意: 此处没有单独写后台页面，复用的wp的post页面，但是需要统一post title为  apiupdatehistory
 * sql按照post_title like 'apiupdatehistory%'查询
 * TODO 改善此流程
 *
 * @package    includes/pages/
 * @subpackage frontend
 *
 * @copyright Copyright (c) 2013, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */
?>
<?php
/**
 * business logic
 */
//TODO 实现分页
$page_no = 1;
$page_size = 100;
$filter_result = apify_get_all_api_update_history_list_by_filters( array('post_title'=>'apiupdatehistory'), $page_no, $page_size);
$api_update_history_list = $filter_result['list'];
?>

<!-- main begin -->
<div class="col-main bg_line">
	<div class="main-wrap">
		<div>
			<h1 class="title">API变更记录</h1>
		</div>
		<?php 
		if( !empty($api_update_history_list) ) { 
			foreach( $api_update_history_list as $apiUpdateHistoryObj ) {
				$plain_content = strip_tags($apiUpdateHistoryObj->post_content);
				if( !empty( $plain_content ) ) { 
		?>
		<div class="doc-detail-bd" id="bd">
			<h1><?php echo $apiUpdateHistoryObj->post_modified ?></h1>
			<div>
				<?php echo stripslashes( $apiUpdateHistoryObj->post_content );  ?>
			</div>
		</div>
		<?php 
				} //end if
			} //end foreach
		} else {
		?>
		<h3>No History!!!</h3>
		<?php } //end else ?>
	</div>
</div>
<!-- main end -->
