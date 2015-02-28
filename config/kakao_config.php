<?php
// Request Token 요청 주소
$request_token_url = 'https://nid.naver.com/naver.oauth?mode=req_req_token';  // 신버전

// 사용자 인증 URL
$authorize_url = 'https://nid.naver.com/naver.oauth?mode=auth_req_token'; //신버전

// Access Token URL
$access_token_url = 'https://nid.naver.com/naver.oauth?mode=req_acc_token'; //신버전

// Consumer 정보 (Consumer를 등록하면 얻어올 수 있음.)
$hotpack_social_login = get_option('hotpack_social_login');
$defaults = array(
		'kakao'	=> array(
				'used'	=> 'disable',
				'consumer_key'	=> '',
				'consumer_secret'	=> ''
			)
	);
$hotpack_social_login = wp_parse_args( $hotpack_social_login, $defaults );
$consumer_key = $hotpack_social_login['kakao']['consumer_key'];
$consumer_secret = $hotpack_social_login['kakao']['consumer_secret'];;
$callback_url = site_url('?hsl_callback=kakao');

// API prefix (보호된 자원이 있는 URL의 prefix)
$api_url = 'http://openapi.naver.com';

// Service Provider와 통신할 인터페이스를 갖고 있는 객체 생성.
//$oauth = new OAuth($consumer_key, $consumer_secret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_AUTHORIZATION);
?>