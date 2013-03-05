<?php
/**
 * 前台显示api数据对象页面
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
$data_model_desc = 'data_model_desc_'.$lang_code;
$model_field_desc = 'model_field_desc_'.$lang_code;

//主标题
$_main_title = array('en'=> 'Simple Description', 'zh'=> '简要描述');
$_second_title = array('en'=> 'Attribute', 'zh'=> '属性');
$_display_field_title = array('name'=> array('en'=>'Name','zh'=>'名称'), 'type'=> array('en'=>'Type','zh'=>'类型'), 'sample'=> array('en'=>'Sample', 'zh'=>'示例'), 'desc'=> array('en'=>'Description', 'zh'=>'描述'));

//获取数据类型id=>name的映射关系
$api_data_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_name');
//获取数据类型id=>type的映射关系
$api_data_type_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_type');
//获取数据对象id=>name的映射关系
$api_data_model_selection_array = apify_get_api_data_model_selection(array('status'=>1, 'visiable'=>1), 'data_model_name');

//获取当前api数据对象详情
$api_data_model_list = apifyDMSGetApiDataModelList( array('visiable'=>1, 'status'=>1), array( intval($_GET['id']) ) );
$currentApiDataModelObj = $api_data_model_list[0];

//获取有效可见的api对象字段列表
$api_model_field_list = apifyDMSGetApiModelFieldList( array('visiable'=>1, 'status'=>1, 'data_model_id'=>intval($_GET['id'])) );

?>

<!-- main begin -->
<div class="col-main bg_line">
	<div class="main-wrap">
		<div class="title-wrap">
			<h1 class="title"><?php echo $currentApiDataModelObj->data_model_name; ?></h1>  
		</div>

		<div class="api-detail-bd" id="bd">
			<!-- ued div start 不知何用-->
			<div>
				<div class="content-first">
					<h2 class="head-title" id=""><?php echo $_main_title[$lang_code]; ?></h2>
					<p class="introduction"><?php echo $currentApiDataModelObj->$data_model_desc; ?></p>
				</div>
				<h2 class="api-content-title"><a name="testtool"></a></h2>
						
				<div>
					<h2 class="head-title" id=""><?php echo $_second_title[$lang_code]; ?></h2>
					<table class="api-table" cellspacing="0">
						<thead>
							<tr>
								<th class="left" width="20%"><?php echo $_display_field_title['name'][$lang_code]; ?></th>
		                        <th class="middle" width="20%"><?php echo $_display_field_title['type'][$lang_code]; ?></th>
		                        <th class="left" width="20%"><?php echo $_display_field_title['sample'][$lang_code]; ?></th>
		                        <th class="left" width="40%"><?php echo $_display_field_title['desc'][$lang_code]; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							/**
							 * display base or Object
							 */
							foreach( $api_model_field_list as $i=>$apiModelFieldObj ) {
							$api_data_type_type = intval( $api_data_type_type_selection_array[$apiModelFieldObj->model_field_data_type_id] );
							if( in_array( $api_data_type_type, array(9, 91) ) ) { //1 basic, 9 model, 91 model list
								$data_type_link_url = get_site_url().'/api-data-model?id='.$apiModelFieldObj->model_field_model_id;
								$data_type_name = $api_data_model_selection_array[$apiModelFieldObj->model_field_model_id];
								if( $api_data_type_type == 91 ) {
									$data_type_name .= '[]';	
								}
							} else {
								$data_type_link_url = get_site_url().'/api-data-type-list';
								$data_type_name = $api_data_type_selection_array[$apiModelFieldObj->model_field_data_type_id];
							}
							?>
							<tr class="<?php echo (($i % 2) == 0) ? 'odd' : 'even'; ?>">
								<td class="left"><?php echo $apiModelFieldObj->model_field_name; ?></td>
								<td class="middle"><a href="<?php echo $data_type_link_url; ?>" class="link"><?php echo $data_type_name; ?></a></td>
								<td class="left"><?php echo stripslashes($apiModelFieldObj->model_field_sample); ?></td>
								<td class="left"><?php echo $apiModelFieldObj->$model_field_desc; ?></td>		
							</tr>
							<?php } //end foreach ?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- ued div end-->
		</div>
	</div>
</div>
<!-- main end -->
