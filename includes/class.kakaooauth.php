<?php
require_once(dirname(dirname(__FILE__)).'/config/kakao_config.php');
if(!class_exists('HotPackOAuth'))
	require_once(dirname(__FILE__).'/OAuth/HotPackOAuth.php');
if(!class_exists('OAuthConsumer'))
  require_once(dirname(__FILE__).'/OAuth.php');
class KakaoOAuth{
	 /* Contains the last HTTP status code returned. */
  public $http_code;
  /* Contains the last API call. */
  public $url;
  /* Set up the API root URL. */
  public $host = "";
  /* Set timeout default. */
  public $timeout = 30;
  /* Set connect timeout. */
  public $connecttimeout = 30; 
  /* Verify SSL Cert. */
  public $ssl_verifypeer = FALSE;
  /* Respons format. */
  public $format = 'json';
  /* Decode returned json data. */
  public $decode_json = TRUE;
  /* Contains the last HTTP headers returned. */
  public $http_info;
  /* Set the useragnet. */
  public $useragent = 'HotPackOAuth v0.1';
  private $access_token;
  function accessTokenURL(){
    return 'https://kauth.kakao.com/oauth/token';
  }
  function __construct($consumer_key, $code) {
    $this->consumer_key = $consumer_key;
    $this->code = $code;
    
  }
 
  function getAccessToken() {
    $CLIENT_ID     = $this->consumer_key; 
    $REDIRECT_URI  = home_url('?hsl_callback=kakao'); 
    $TOKEN_API_URL = "https://kauth.kakao.com/oauth/token"; 

    $code   = $this->code; 
    $params = sprintf( 'grant_type=authorization_code&client_id=%s&redirect_uri=%s&code=%s', $CLIENT_ID, $REDIRECT_URI, $code); 

     $opts = array( 
       CURLOPT_URL => $TOKEN_API_URL, 
       CURLOPT_SSL_VERIFYPEER => false, 
        CURLOPT_SSLVERSION => 1, // TLS
       CURLOPT_POST => true, 
       CURLOPT_POSTFIELDS => $params, 
       CURLOPT_RETURNTRANSFER => true, 
       CURLOPT_HEADER => false 
     ); 

     $curlSession = curl_init(); 
     curl_setopt_array($curlSession, $opts); 
     $accessTokenJson = curl_exec($curlSession); 
     curl_close($curlSession); 
     $accessToken = json_decode($accessTokenJson);

     return $accessToken->access_token; 
  }

  
  function get_user_profile($access_token){
  	$TOKEN_API_URL = "https://kapi.kakao.com/v1/user/me"; 
 
   $opts = array( 
     CURLOPT_URL => $TOKEN_API_URL, 
     CURLOPT_SSL_VERIFYPEER => false, 
     CURLOPT_SSLVERSION => 1, 
     CURLOPT_POST => true, 
     CURLOPT_POSTFIELDS => false, 
     CURLOPT_RETURNTRANSFER => true, 
     CURLOPT_HTTPHEADER => array( 
    "Authorization: Bearer " . $access_token 
    )
   ); 
   
   $curlSession = curl_init(); 
   curl_setopt_array($curlSession, $opts); 
   $accessTokenJson = curl_exec($curlSession); 
   

   curl_close($curlSession);
   $userinfo = json_decode($accessTokenJson);
   $userInfo = array(
      'userID' => $userinfo->id,
      'user_email'  => $userinfo->id.'@kakao.com' ,
      'nickname' => $userinfo->properties->nickname,
      'profImg' => $userinfo->properties->thumbnail_image
    );
   return $userInfo;
  }
  /**
   * GET wrapper for oAuthRequest.
   */
  function get($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'GET', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }
  
  /**
   * POST wrapper for oAuthRequest.
   */
  function post($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'POST', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * DELETE wrapper for oAuthReqeust.
   */
  function delete($url, $parameters = array()) {
    $response = $this->oAuthRequest($url, 'DELETE', $parameters);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * Format and sign an OAuth / API request
   */
  function oAuthRequest($url, $method, $parameters) {
    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
      $url = "{$this->host}{$url}.{$this->format}";
    }
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
    $request->sign_request($this->sha1_method, $this->consumer, $this->token);
    switch ($method) {
    case 'GET':
      return $this->http($request->to_url(), 'GET');
    default:
      return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
    }
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
  function http($url, $method, $postfields = NULL) {
    $this->http_info = array();
    $ci = curl_init();
    /* Curl settings */

    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
    curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ci, CURLOPT_SSLVERSION, 3);
    
    
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
    }

    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);print_r($postfields);
    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
    $this->url = $url;
    curl_close ($ci);
    return $response;
  }

  /**
   * Get the header info to store.
   */
  function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
	/**
	* Set API URLS
	*/
	//function authenticateURL() { return 'https://api.twitter.com/oauth/authenticate'; }
	function authorizeURL()    { return 'https://nid.naver.com/naver.oauth?mode=auth_req_token'; }
	function requestTokenURL() { return 'https://nid.naver.com/naver.oauth?mode=req_req_token'; }
	private function generate_state(){
		$mt = microtime();
		$rand = mt_rand();
		$this->state = md5( $mt . $rand );
	}
	public function set_state(){
		$this->generate_state();
		$_SESSION['state'] = $this -> state;
	}
}