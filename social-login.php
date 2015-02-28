<?php
/*
 * Plugin Name: 소셜 로그인
 * Plugin URI: http://wordpress.org/extend/plugins/hotpack/
 * Description: 페이스북, 구글 계정등을 통해 워드프레스에 로그인 할 수 있도록 해주는 플러그인입니다.(페이스북, 트위터, 구글, 카카오, 네이버 지원)
 * Author: Stackr Inc.
 * Version: 1.0
 * Author URI: http://stackr.co.kr
 * License: GPL2+
 * Text Domain: hotpack
 * Domain Path: /languages/
 */
require_once(dirname(__FILE__).'/includes/class.sociallogin.php');

if(class_exists('SocialLogin')){
	new SocialLogin();
}
?>