<?php
/**
 * ajax.php
 * ajax处理函数集
 *
 * NOTE: 函数命名必须以 litb_ajax 开头，便于自动载入
 * Example: litb_ajax_validate_email_exists()
 *
 * @package    includes
 * @subpackage functions
 *
 * @copyright Copyright (c) 2010, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */

/**
 * 生成app key和app密钥
 *
 * app密钥生成规则: 16位app_id的md5密文 + 16为随机字母数字串
 *
 */
function apify_ajax_generate_app_key_secret( $app_id ) {

	$app_key = apify_generate_app_key( $app_id );
	$app_secret = apify_generate_app_secret( $app_id );	

	$result = array(
			'app_key'		=> $app_key,
			'app_secret'	=> $app_secret,
	);	

	echo json_encode($result);
	//覆盖admin-ajax.php中最后默认输出的0，否则无法判断布尔值
	die();
}
