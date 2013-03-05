<?php
/**
 * 前台api文档系统侧导航页面
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
$navigator_type = 0; //0=> home 1=> api group list 2=>api item list
$side_navigator_list = array();

$lang_code = (isset($_GET['lang']) && (strtolower($_GET['lang']) == 'en')) ? 'en' : 'zh';
$item_desc = 'item_desc_'.$lang_code;
$group_title = 'group_title_'.$lang_code;
$group_desc = 'group_desc_'.$lang_code;
//主标题
$_back_title = array('en'=> 'Back to', 'zh'=> '返回');
$_apidoc_title = array('en'=> 'API Doc', 'zh'=> 'API文档');

if( $pagetype == 'api-item') {
	$navigator_type = 2;
	
	//获取当前api Item详情
	$api_item_list = apifyDMSGetApiItemList( array('visiable'=>1, 'status'=>1), array( intval($_GET['id']) ) );
	$currentApiItemObj = $api_item_list[0];
	//获取当前api item所属分组详情
	$api_group_list = apifyDMSGetApiGroupList( array('visiable'=>1, 'status'=>1, 'group_id'=>intval($currentApiItemObj->item_group_id)) );
	$currentApiGroupObj = $api_group_list[0];

	//获取有效可见的api类目列表
	$side_navigator_list = apifyDMSGetApiItemList( array('visiable'=>1, 'status'=>1, 'item_group_id'=>intval($currentApiItemObj->item_group_id)) );
} elseif( $pagetype == 'api-item-list' ) {
	$navigator_type = 1;
	
	//获取当前api分组详情
	$api_group_list = apifyDMSGetApiGroupList( array('visiable'=>1, 'status'=>1, 'group_id'=>intval($_GET['group_id'])) );
	$currentApiGroupObj = $api_group_list[0];
	//获取有效可见的api类目列表
	$side_navigator_list = apifyDMSGetApiGroupList( array('visiable'=>1, 'status'=>1) );
}

//TODO
//文档全局导航菜单功能
?>

<!--Side Navigator begin-->
<div class="col-sub">
<?php if( ($onlyshowglobalnavigator == false) && ($navigator_type === 2 ) ) { ?>
	<div class="APIgory">
		<ul>
			<li>
				<div class="APIgory-title">
					<a href="<?php echo get_site_url().'/api-item-list?group_id='.intval($currentApiGroupObj->group_id); ?>"><?php echo $_back_title[$lang_code]; ?>&nbsp;<span class="APIgory-sub-title">&nbsp;<?php echo $currentApiGroupObj->$group_title; ?></span></a>
				</div>
			</li>
			<?php foreach( $side_navigator_list as $apiItemObj ) { ?>
			<li>						
				<div class="<?php echo ($apiItemObj->item_id == $currentApiItemObj->item_id) ? 'APIgory-list focus' : 'APIgory-list'; ?>">
					<div class="APIgory-content">
						<a class="link APIgoryItem title-overflow" href="<?php echo get_site_url().'/api-item?id='.intval($apiItemObj->item_id); ?>" title="<?php echo $apiItemObj->item_name; ?>"><?php echo $apiItemObj->item_name; ?></a>
						<p class="APIgory-intro info-overflow"><?php echo $apiItemObj->$item_desc; ?></p>
					</div>
					<s></s>
				</div>
			</li>
			<?php } //end foreach ?>																						
		</ul>
		<div class="quick-api">
			<a href="<?php echo get_site_url().'/api-group-list'; ?>"><?php echo $_apidoc_title[$lang_code]; ?></a>
		</div>
	</div>
<?php } //end if ?>

<?php if( ($onlyshowglobalnavigator == false) && ($navigator_type === 1 ) ) { ?>
	<div class="api api-scrollbar">
		<ul class="APIgory">
			<li>
				<div class="APIgory-title">
					<a href="<?php echo get_site_url().'/api-group-list'; ?>" title="<?php echo $_apidoc_title[$lang_code]; ?>"><?php echo $_back_title[$lang_code]; ?>&nbsp;<span class="APIgory-sub-title"><?php echo $_apidoc_title[$lang_code]; ?></span></a>
				</div>
			</li>
			<?php foreach( $side_navigator_list as $apiGroupObj ) { ?>
			<li>
				<p class="<?php echo ($apiGroupObj->group_id == $currentApiGroupObj->group_id) ? 'APIgory-list focus' : 'APIgory-list' ?>">
					<a class="link info-overflow" href="<?php echo get_site_url().'/api-item-list?group_id='.intval($apiGroupObj->group_id); ?>" title="<?php echo $apiGroupObj->$group_desc; ?>"><?php echo $apiGroupObj->$group_title; ?></a>
				</p>
			</li>
			<?php } //end foreach ?>
		</ul>
	</div>
<?php } //end if ?>

<?php if( ($onlyshowglobalnavigator == true) ) { ?>
	<div class="category">
		<ul class="level-one">
			<li class=" ">
				<p class="category-item first title-overflow  hasSub">
					<span></span>
						<a class="link" href="#" title="平台介绍">平台介绍</a>
				</p>
			</li>
			<li class=" ">
				<p class="category-item first title-overflow  hasSub">
					<span></span>
						<a class="link" href="<?php echo get_site_url().'/api-group-list'; ?>" title="平台介绍">API文档</a>
				</p>
			</li>
			<li class=" ">
				<p class="category-item first title-overflow  hasSub">
					<span></span>
						<a class="link" href="<?php echo get_site_url().'/api-update-history-list'; ?>" title="平台介绍">API文档变更历史</a>
				</p>
			</li>
			<li class=" ">
				<p class="category-item first title-overflow  hasSub">
					<span></span>
						<a class="link" href="<?php echo get_site_url().'/api-xuqiu-list'; ?>" title="平台介绍">API需求进度</a>
				</p>
			</li>
			<li class=" ">
				<p class="category-item first title-overflow  hasSub">
					<span></span>
						<a class="link" href="<?php echo get_option('apify_dms_api_tool_url'); ?>" title="平台介绍">沙箱环境</a>
				</p>
			</li>
			<li class=" ">
				<p class="category-item first title-overflow  hasSub">
					<span></span>
						<a class="link" href="#" title="平台介绍">名词解释</a>
				</p>
			</li>
			<li class=" ">
				<p class="category-item first title-overflow  hasSub">
					<span></span>
						<a class="link" href="#" title="平台介绍">常见问题</a>
				</p>
			</li>
		</ul>
		<div class="quick-api">
			<a href="<?php echo get_site_url().'/api-group-list'; ?>"><?php echo $_apidoc_title[$lang_code]; ?></a>
		</div>
	</div>
<?php } //end if ?>
</div>
<!--Side Navigator end-->
