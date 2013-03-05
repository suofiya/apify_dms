<?php
/**
 * 前台显示api类目列表页面
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
$lang_code = (isset($_GET['lang']) && (strtolower($_GET['lang']) == 'en')) ? 'en' : 'zh';
$group_title = 'group_title_'.$lang_code;
$group_desc = 'group_desc_'.$lang_code;
$item_desc = 'item_desc_'.$lang_code;
$data_model_desc = 'data_model_desc_'.$lang_code;

//主标题
$_main_title = array('en'=> 'API Category List', 'zh'=> 'API类目列表');
$_second_title = array('en'=> 'DataModel List', 'zh'=> '数据结构列表');

//获取当前api类目详情
$api_group_list = apifyDMSGetApiGroupList( array('visiable'=>1, 'status'=>1), array(intval($_GET['group_id'])) );
$currentApiGroupObj = $api_group_list[0];

//获取有效可见的api类目列表
$api_item_list = apifyDMSGetApiItemList( array('visiable'=>1, 'status'=>1, 'item_group_id'=>intval($_GET['group_id'])) );
//获取当前api类目下所有api返回数据对象列表
$ids = apifyDMSGetApiResultModelIdsListByGroupId( intval($_GET['group_id']) );
if( !empty($ids) ) {
	$api_data_model_list = apifyDMSGetApiDataModelList( array('visiable'=>1, 'status'=>1) , $ids);
} else {
	$api_data_model_list = array();
}
?>

<!-- main begin -->
<div class="col-main bg_line">
	<div class="main-wrap">
		<h1 class="title"><?php echo $currentApiGroupObj->$group_title; ?></h1>
		<p class="title-intro"><?php echo $currentApiGroupObj->$group_desc; ?></p>
						
		<div class="sub-wrap">
			<?php if( !empty($api_item_list) ) { // ?>
			<h3 class="head-title"><?php echo $_main_title[$lang_code]; ?></h3>
			<div class="api-list hover-list">
				<?php foreach( $api_item_list as $apiItemObj ) { //list ?>
				<p>
					<span class="l">
						<s class="<?php echo ($apiItemObj->item_type == 2) ? 'security-bg' : 'normal-bg'; ?>"></s>
						<a class="link" href="<?php echo get_site_url().'/api-item?id='.intval($apiItemObj->item_id); ?>" title="<?php echo $apiItemObj->item_name; ?>"><?php echo $apiItemObj->item_name; ?></a>
					</span>
					<span><?php echo $apiItemObj->$item_desc; ?></span>
				</p>
				<?php } //end foreach ?>
			</div>
			<?php } //end if ?>
		</div>
									
		<div class="sub-wrap">
			<?php if( !empty($api_data_model_list) ) { // ?>
			<h3 class="head-title"><?php echo $_second_title[$lang_code]; ?></h3>
			<div class="api-list">
				<?php foreach( $api_data_model_list as $apiDataModelObj ) { //list ?>
				<p>
					<span class="l">
						<a class="link" href="<?php echo get_site_url().'/api-data-model?id='.intval($apiDataModelObj->data_model_id); ?>" title="<?php echo $apiDataModelObj->data_model_name; ?>"><?php echo $apiDataModelObj->data_model_name; ?></a>
					</span>
					<span><?php echo $apiDataModelObj->$data_model_desc; ?></span>
				</p>
				<?php } //end foreach ?>
			</div>
			<?php } //end if ?>
		</div>
	</div>
</div>
<!-- main end -->
