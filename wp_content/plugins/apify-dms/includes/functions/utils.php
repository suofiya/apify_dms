<?php
/**
 * utils.php
 * 通用函数集
 *
 * @package    includes
 * @subpackage functions
 *
 * @copyright Copyright (c) 2010, lightinthebox.com
 * @version $Id$
 * @author liutao@lighitnthebox.com
 */

/**
 * This function validates a plain text password with an encrpyted password
 *
 * $param string $plain 
 * $param string $encrypted
 *
 */
function apify_validate_password($plain, $encrypted) {
    if (apify_not_null($plain) && apify_not_null($encrypted)) {
	// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
}

/**
 * This function makes a new password from a plaintext password. 
 *
 * @param string $plain
 *
 */
function apify_encrypt_password($plain) {
    $password = '';
	
    for ($i=0; $i<10; $i++) {
      	$password .= apify_rand();
    }
    
    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
}

/**
 * 发送邮件Function
 *
 * 采用SMTP Plugins(cimy-swift-smpt),该插件使用SMTP替换PhpMailer,覆写wordpress默认wp_mail方法
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $header
 *
 */
function apify_sendMail($to, $subject, $message, $type='html', $headers = '', $attachments = array(), $echo_error = false) {
	
	// To send HTML mail, the Content-type header must be set
	if( !empty($type) && (strtolower($type) == 'html') ) {
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type: text/html; charset=utf-8';
	}
	// set From
	if( is_array($headers) && !empty($headers) ) {
		$headers[] = 'From: LightInTheBox_HR <apify_recruit@system.lightinthebox.com>';
	} else {
		$headers = 'From: LightInTheBox_HR <apify_recruit@system.lightinthebox.com>'. "\r\n";
	}

	//sendMail
	wp_mail( $to, $subject, $message, $headers, $attachments, $echo_error );
}



/**
 * 生成app key
 */
function apify_generate_app_key( $app_id='', $length=8 ) {
	$app_key = '';
 	
	$chars = 'ABCDEFGHIJKMNOPQRSTUVWXYZ0123456789';
 	$max = strlen($chars) - 1;
	//
	mt_srand((double)microtime() * 1000000);
	//
	for($i = 0; $i < $length; $i++) {
		$app_key .= $chars[mt_rand(0, $max)];
	}

 	return $app_key;
}


/**
 * 生成app密钥
 *
 * 生成规则: 16位app_id的md5密文 + 16为随机字母数字串
 */
function apify_generate_app_secret( $app_id='', $length=8 ) {
	$app_secret = '';
		
	/**
	 * STEP_1: 私钥先加上16位app_id的md5密文
	 */
	$app_secret .= substr( md5($app_id), 8, 16 );

	/**
	 * STEP_2: 在加上16为随机字母数字串
	 */
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
 	$max = strlen($chars) - 1;
	//播下一个更好的随机数发生器种子	
	mt_srand((double)microtime() * 1000000);
	
	//
	for($i = 0; $i < $length; $i++) {
  		$app_secret .= $chars[mt_rand(0, $max)];
	}

 	return $app_secret;
}
