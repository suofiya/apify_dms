<?php
/**
 * 前台显示api数据类型列表页面
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
$data_type_desc = 'data_type_desc_'.$lang_code;

//主标题
$_main_title = array('en'=> 'Data Type Introduction', 'zh'=> '数据类型说明');
$_second_title = array('en'=> 'Example', 'zh'=> '例如');

//获取有效可见的api数据类型列表
$api_data_type_list = apifyDMSGetApiDataTypeList( array('visiable'=>1, 'status'=>1) );

?>

<!-- main begin -->
<div class="col-main bg_line">
	<div class="main-wrap">
		<div>
			<h1 class="title"><?php echo $_main_title[$lang_code]; ?></h1>
		</div>
		<?php if( !empty($api_data_type_list) ) { // ?>
		<div class="doc-detail-bd" id="bd">
			<?php foreach( $api_data_type_list as $apiDataTypeObj ) { ?>
			<p>&nbsp;</p>
			<h3><span><?php echo $apiDataTypeObj->data_type_name; ?></span></h3>
			<p>&nbsp;</p>
			<table>
				<tbody>
					<tr>
						<td>
							<dl>
								<dd><?php echo $apiDataTypeObj->$data_type_desc; ?>, <?php echo $_second_title[$lang_code]; ?>: <?php echo $apiDataTypeObj->data_type_sample; ?></dd>
							</dl>
						</td>
					</tr>
				</tbody>
			</table>
			<?php } //end foreach ?>	
		</div>
		<?php } else { ?>
		<div class="no-results">
		<?php echo 'No Results!!!'; ?>
		</div>
		<?php } //end else ?>
	</div>
</div>
<!-- main end -->
