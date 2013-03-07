<?php
/**
 * 前台显示api Item页面
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
$item_desc = 'item_desc_'.$lang_code;
$item_note = 'item_note_'.$lang_code;
$parameter_desc = 'parameter_desc_'.$lang_code;
$result_desc = 'result_desc_'.$lang_code;
$error_code_desc = 'error_code_desc_'.$lang_code;
$data_model_desc = 'data_model_desc_'.$lang_code;
$model_field_desc = 'model_field_desc_'.$lang_code;

//主标题
$_authorized_title = array('en'=> 'Authorized Type', 'zh'=> 'API认证类型');
$_sys_parameter_title = array('en'=> 'System Parameters', 'zh'=> '系统级参数');
$_api_parameter_title = array('en'=> 'API Parameters', 'zh'=> 'API接口级参数');
$_result_title = array('en'=> 'API Result', 'zh'=> '返回结果');
$_result_example_title = array('en'=> 'Result Example', 'zh'=> '返回示例');
$_errorcode_title = array('en'=> 'Error Code', 'zh'=> '错误码');
$_apitool_title = array('en'=> 'API Tools', 'zh'=> 'API工具');
$_apitool_desc = array('en'=> 'API Test Tool', 'zh'=> 'API沙盒测试工具');

$_xml_result_example_title = array('en'=> 'XML Format', 'zh'=> 'XML数据格式');
$_json_result_example_title = array('en'=> 'Json Format', 'zh'=> 'Json数据格式');

//参数显示标题
$_display_parameter_title = array(
									'name'=> array('en'=>'Name','zh'=>'名称'), 
									'type'=> array('en'=>'Type','zh'=>'类型'), 
									'mode'=> array('en'=>'Required Mode','zh'=>'是否必须'), 
									'sample'=> array('en'=>'Sample', 'zh'=>'示例值'),
									'default'=> array('en'=>'Default', 'zh'=>'默认值'),
									'enum'=> array('en'=>'Enum', 'zh'=>'可选值'),
									'rule'=> array('en'=>'Rule', 'zh'=>'约束条件'),
									'desc'=> array('en'=>'Description', 'zh'=>'描述')
							);
//返回值显示标题
$_display_result_title = array(
									'name'=> array('en'=>'Name','zh'=>'名称'), 
									'type'=> array('en'=>'Type','zh'=>'类型'), 
									'desc'=> array('en'=>'Description', 'zh'=>'描述')
							);

//错误码显示标题
$_display_errorcode_title = array(
									'name'=> array('en'=>'Error Code','zh'=>'错误码'), 
									'desc'=> array('en'=>'Error Description', 'zh'=>'错误描述'),
									'solution'=> array('en'=>'Error Solution', 'zh'=>'解决方案'),
							);

//获取参数必须类型mode_id=>name的映射关系
$mode_selection_array = array('-1'=> __('-- mode --'), '1'=>__('required'), '2'=>__('optional'), '91'=>__('special required'), '92'=>__('special optional'));
//获取数据类型id=>name的映射关系
$api_data_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_name');
//获取数据类型id=>type的映射关系
$api_data_type_type_selection_array = apify_get_api_data_type_selection(array('status'=>1, 'visiable'=>1), 'data_type_type');
//获取数据对象id=>name的映射关系
$api_data_model_selection_array = apify_get_api_data_model_selection(array('status'=>1, 'visiable'=>1), 'data_model_name');

//获取当前api Item详情
$api_data_item_list = apifyDMSGetApiItemList( array('visiable'=>1, 'status'=>1), array( intval($_GET['id']) ) );
$currentApiItemObj = $api_data_item_list[0];

//获取当前item对应的系统级参数列表
$sys_parameter_list = apifyDMSGetSysParameterList( array('visiable'=>1, 'status'=>1, 'item_id'=>0) );
//获取当前item对应的api级参数列表
$api_parameter_list = apifyDMSGetApiParameterList( array('visiable'=>1, 'status'=>1, 'item_id'=>intval($_GET['id'])) );
//获取当前item对应的返回结果列表
$api_result_list = apifyDMSGetApiResultList( array('visiable'=>1, 'status'=>1, 'item_id'=>intval($_GET['id'])) );
//获取当前item对应的ErrorCode列表
$ids = !empty($currentApiItemObj->item_errorcode_ids) ? explode(',', $currentApiItemObj->item_errorcode_ids) : array();
if( !empty($ids) ) {
	$api_errorcode_list = apifyDMSGetApiErrorCodeList( array('visiable'=>1, 'status'=>1), $ids );
} else {
	$api_errorcode_list = array();
}
?>

<!-- main begin -->
<div class="col-main bg_line">
	<div class="main-wrap">
		<div class="title-wrap">
			<h1 class="<?php echo ($currentApiItemObj->item_type == 2) ? 'security-bg' : 'normal-bg'; ?> title"><?php echo $currentApiItemObj->item_name; ?></h1>  	
			<ul class="api-sub-title layout clearfix">
				<li class="sub-title"><a class="link" href="#authorize"><?php echo $_authorized_title[$lang_code]; ?></a></li>
				<li class="sub-title"><a class="link" href="#sys-param"><?php echo $_sys_parameter_title[$lang_code]; ?></a></li>
				<li class="sub-title"><a class="link" href="#api-param"><?php echo $_api_parameter_title[$lang_code]; ?></a></li>
				<li class="sub-title"><a class="link" href="#result"><?php echo $_result_title[$lang_code]; ?></a></li>
				<li class="sub-title"><a class="link" href="#example"><?php echo $_result_example_title[$lang_code]; ?></a></li>
				<li class="sub-title"><a class="link" href="#error-code"><?php echo $_errorcode_title[$lang_code]; ?></a></li>
				<li class="sub-title"><a class="link" href="#API-tools"><?php echo $_apitool_desc[$lang_code]; ?></a></li>
			</ul>
		</div>

		<div class="api-detail-bd" id="bd">					
			<p class="title-intro"><?php echo stripslashes($currentApiItemObj->$item_note); ?></p>
			<div class="content-main">
				<div class="content-first">
					<h2 class="head-title" id="authorize">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_down.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-authorize')">
						<?php echo $_authorized_title[$lang_code]; ?>
					</h2>
					<div id="content-authorize" style="">
						<p class="introduction"><?php echo ($currentApiItemObj->item_type == 2) ? 'Security (need provide sessionkey)' : 'Normal'; ?></p>
					</div>	
				</div>
					
				<div>
					<h2 class="head-title" id="sys-param">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_right.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-sys-param')">
						<?php echo $_sys_parameter_title[$lang_code]; ?>
					</h2>
					<div id="content-sys-param" style="display:none;">
						<table class="api-table" cellspacing="0">
							<?php if( !empty($sys_parameter_list) ) { ?>
							<thead>
								<tr>	
									<th class="middle" width="15%"><?php echo $_display_parameter_title['name'][$lang_code]; ?></th>	
									<th class="middle" width="15%"><?php echo $_display_parameter_title['type'][$lang_code]; ?></th>	
									<th class="middle" width="15%"><?php echo $_display_parameter_title['mode'][$lang_code]; ?></th>	
									<th class="left" width="55%"><?php echo $_display_parameter_title['desc'][$lang_code]; ?></th>	
								</tr>
							</thead>	
							<tbody>
								<?php foreach( $sys_parameter_list as $i=>$sysParameterObj) { ?>
								<tr class="<?php echo (($i % 2) == 0) ? 'odd' : 'even'; ?>">
									<td class="middle"><?php echo $sysParameterObj->parameter_name; ?></td>
									<td class="middle"><a href="<?php echo get_site_url().'/api-data-type-list'; ?>" class="link" target="_blank"><?php echo $api_data_type_selection_array[$sysParameterObj->parameter_data_type_id]; ?></a></td>
									<td class="middle"><?php echo $mode_selection_array[$sysParameterObj->parameter_required_mode]; ?></td>
									<td class="left"><?php echo $sysParameterObj->$parameter_desc; ?></td>
								</tr>
								<?php } //end foreach ?>
							</tbody>
							<?php } else { ?>
							<h3>No Result!!!</h3>
							<?php } //end else ?>
						</table>
					</div>
				</div>
					
				<div>
					<h2 class="head-title" id="api-param">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_down.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-api-param')">
						<?php echo $_api_parameter_title[$lang_code]; ?>
					</h2>
					<div id="content-api-param">
						<table class="api-table" cellspacing="0">
							<?php if( !empty($api_parameter_list) ) { ?>
							<thead>
								<tr>	
									<th width="10%" class="middle"><?php echo $_display_parameter_title['name'][$lang_code]; ?></th>
									<th width="10%" class="middle"><?php echo $_display_parameter_title['type'][$lang_code]; ?></th>
									<th width="10%" class="middle"><?php echo $_display_parameter_title['mode'][$lang_code]; ?></th>
									<th width="15%" class="left"><?php echo $_display_parameter_title['sample'][$lang_code]; ?></th>
									<th width="10%" class="left"><?php echo $_display_parameter_title['default'][$lang_code]; ?></th>
									<th width="45%" class="left"><?php echo $_display_parameter_title['desc'][$lang_code]; ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach( $api_parameter_list as $i=>$apiParameterObj) { ?>
								<tr class="<?php echo (($i % 2) == 0) ? 'odd' : 'even'; ?>">
									<td class="middle"><?php echo $apiParameterObj->parameter_name; ?></td>
									<td class="middle"><a href="<?php echo get_site_url().'/api-data-type-list'; ?>" class="link" target="_blank"><?php echo $api_data_type_selection_array[$apiParameterObj->parameter_data_type_id]; ?></a></td>
									<td class="middle"><?php echo $mode_selection_array[$apiParameterObj->parameter_required_mode]; ?></td>
									<td class="left"><?php echo stripslashes($apiParameterObj->parameter_sample); ?></td>
									<td class="left"><?php echo stripslashes($apiParameterObj->parameter_default_value); ?></td>
									<td class="left"><?php echo stripslashes($apiParameterObj->$parameter_desc); ?></td>
								</tr>
								<?php } //end foreach ?>
							</tbody>
							<?php } else { ?>
							<h3 class="no-data">No API Parameters.</h3>
							<?php } //end else ?>
						</table>
					</div>
				</div>
					
				<div>
					<h2 class="head-title" id="result">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_down.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-result')">
						<?php echo $_result_title[$lang_code]; ?>
					</h2>
					<div id="content-result">
					<div class="sub-column-title1" style="height: 30px;margin-top: -10px;line-height: 30px;margin-bottom: 20px;"><?php echo stripslashes($currentApiItemObj->item_response); ?></div>
						<table class="api-table" cellspacing="0">
							<?php if( !empty($api_result_list) ) { ?>
							<thead>
								<tr>	
									<th class="left" width="30%"><?php echo $_display_result_title['name'][$lang_code]; ?></th>	
									<th class="middle" width="20%"><?php echo $_display_result_title['type'][$lang_code]; ?></th>	
									<th class="left" width="50%"><?php echo $_display_result_title['desc'][$lang_code]; ?></th>	
								</tr>
							</thead>	
							<tbody>
								<?php foreach( $api_result_list as $i=>$apiResultObj) { ?>
								<tr class="<?php echo (($i % 2) == 0) ? 'odd' : 'even'; ?>">
									<td class="left"><?php echo $apiResultObj->result_name; ?></td>
									<td class="middle">
										<?php
										/**
										 * display base or Object
										 */
										$api_data_type_type = intval( $api_data_type_type_selection_array[$apiResultObj->result_data_type_id] );
										if( in_array( $api_data_type_type, array(9, 91) ) ) { //1 basic, 9 model, 91 model list
											$data_type_link_url = get_site_url().'/api-data-model?id='.$apiResultObj->result_model_id;
											$data_type_name = $api_data_model_selection_array[$apiResultObj->result_model_id];
											if( $api_data_type_type == 91 ) {
												$data_type_name .= '[]';	
											}
										} else {
											$data_type_link_url = get_site_url().'/api-data-type-list';
											$data_type_name = $api_data_type_selection_array[$apiResultObj->result_data_type_id];
										}
										?>
										<a href="<?php echo $data_type_link_url; ?>" class="link"><?php echo $data_type_name; ?></a>
									</td>
									<td class="left"><?php echo $apiResultObj->$result_desc; ?></td>
								</tr>
								<?php } //end foreach ?>
							</tbody>
							<?php } else { ?>
							<h3 class="no-data">No Result.</h3>
							<?php } //end else ?>
						</table>
					</div>
				</div>
				
				<div>
					<h2 class="head-title " id="example">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_right.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-example')">
						<?php echo $_result_example_title[$lang_code]; ?>
					</h2>
					<div id="content-example" style="">
						<p class="data-format"><?php echo $_xml_result_example_title[$lang_code]; ?>:</p>
						<div>
							<?php echo $currentApiItemObj->item_response_example_xml; ?>
						</div>
						<p class="data-format"><?php echo $_json_result_example_title[$lang_code]; ?>:</p>
						<div>
							<?php echo $currentApiItemObj->item_response_example_json_text; ?>
						</div>
					</div>
				</div>
				
				<div>
					<h2 class="head-title" id="error-code">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_down.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-error-code')">
						<?php echo $_errorcode_title[$lang_code]; ?>
					</h2>
					<div id="content-error-code">
						<table class="api-table" cellspacing="0">
							<?php if( !empty($api_errorcode_list) ) { ?>
							<thead>
								<tr>	
									<th class="left" width="30%"><?php echo $_display_errorcode_title['name'][$lang_code]; ?></th>	
									<th class="left" width="30%"><?php echo $_display_errorcode_title['desc'][$lang_code]; ?></th>
									<th class="left" width="40%"><?php echo $_display_errorcode_title['solution'][$lang_code]; ?></th>	
								</tr>
							</thead>
							<tbody>
								<?php foreach( $api_errorcode_list as $i=>$apiErrorCodeObj) { ?>
								<tr class="odd">
									<td class="left"><?php echo $apiErrorCodeObj->error_code_key; ?></td>
									<td class="left"><?php echo $apiErrorCodeObj->$error_code_desc; ?></td>
									<td class="left"><?php echo $apiErrorCodeObj->error_code_note; ?></td>
								</tr>
								<?php } //end foreach ?>
							</tbody>
							<?php } else { ?>
							<h3 class="no-data">No ErrorCode.</h3>
							<?php } //end else ?>
						</table>
					</div>
				</div>
					
				<div class="API-tools">
					<h2 class="head-title" id="API-tools">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_down.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-API-tools')">
						<?php echo $_apitool_desc[$lang_code]; ?>
					</h2>
					<div id="content-API-tools">
						<div class="tools-wrap">
							<a class="tool-test" href="<?php echo get_option('apify_dms_api_tool_url').'&apiName='.$currentApiItemObj->item_name; ?>" target="_blank"><?php echo ''; ?></a>
						</div>
					</div>
				</div>
				
				<div>
					<h2 class="head-title" id="FAQ">
						<img src="http://api.taobao.com/assets/apidetail/img/tri_right.png" style="cursor:pointer;margin-right:5px;" onclick="changeHide(this,'content-FAQ')">
						FAQ
					</h2>
					<div id="content-FAQ" style="display:none;">
						<table class="api-table" cellspacing="0">
							<thead></thead>
								<tbody>
									<tr class="odd">
										<td><b>Q:&nbsp;</b>Test Question?
										</td>
									</tr>
									<tr class="even">
										<td><b>A:&nbsp;</b>Test Answer!!!
										</td>
									</tr>	
								</tbody>
						</table>
					</div>			
				</div>
				
			</div>
		</div>
	</div>
</div>
<!-- main end -->
<!-- script begin -->
<script type="text/javascript">
		function changeHide(imgDom,contentDivId){
			var contentDiv=document.getElementById(contentDivId);
			if(contentDiv.style.display=="none"){
				contentDiv.style.display="";
				imgDom.src="http://api.taobao.com/assets/apidetail/img/tri_down.png";
			}else{
				contentDiv.style.display="none";
				imgDom.src="http://api.taobao.com/assets/apidetail/img/tri_right.png";
			}
		}
</script>
<!-- script end -->
<!-- BackTop begin -->
<div class="tools">
		<a id="J_Issue" class="btn-issue" title="文档反馈" href="javascript:"></a>
		<a id="J_Share" class="btn-share" title="分享文档" href="javascript:"></a>
		<a id="J_Ruturn" class="btn-return" title="返回顶部" href="javascript:"></a>
</div>
<!-- BackTop end -->
