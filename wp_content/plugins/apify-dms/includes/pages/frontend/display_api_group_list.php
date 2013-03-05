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

//主标题
$_main_title = array('en'=> 'API Category List', 'zh'=> 'API类目列表');

//获取有效可见的api类目列表
$api_group_list = apifyDMSGetApiGroupList( array('visiable'=>1, 'status'=>1) );

?>
<!-- main begin -->
<div class="col-main bg_line">
	<div class="main-wrap ">
		<h1 class="title"><?php echo $_main_title[$lang_code]; ?></h1>
			<div class="contentlist-api">
				<ul class="layout">
					<?php foreach( $api_group_list as $apiGroupObj ) { //list ?>
					<li class="api-list-item ">
						<span><a href="<?php echo get_site_url().'/api-item-list?group_id='.intval($apiGroupObj->group_id); ?>" class="link"><?php echo $apiGroupObj->$group_title; ?></a></span>
						<p class="api-list-intro"><?php echo $apiGroupObj->$group_desc; ?></p>
					</li>
					<?php } //end foreach ?>
				</ul>
			</div>
		</div>
</div>
<!-- main end -->
