<?php
class SocialLogin{
	function __construct(){
		add_action( 'admin_menu', array(&$this, 'setting_menu') );//로그인 api 세팅 메뉴
		add_action( 'admin_init',array(&$this,'register_setting') );
		add_action( 'login_form', array(&$this,'social_login_form') );
		add_action ('comment_form_top', array(&$this,'social_login_form') );
		add_action( 'init',array(&$this,'social_login_init') );
		add_action( 'init',array(&$this,'social_login_callback_init') );
		add_filter( 'get_avatar' , array(&$this,'get_avatar') , 1 , 4 );
	}
	function get_avatar($avatar, $id_or_email, $size, $default, $alt){

		if(is_numeric($id_or_email)){
			$profile_img = get_user_meta($id_or_email, 'profileimg',true);
			$logintype = get_user_meta($id_or_email, 'logintype',true);
			if(isset($logintype) && isset($profile_img) && $profile_img != ''){
				switch($logintype){
					case 'naver':
						$alt = __('Naver profile image','hotpack');
						break;
					case 'daum':
						$alt = __('Daum profile image','hotpack');
						break;
					case 'kakao':
						$alt = __('Kakao profile image','hotpack');
						break;
				}
				$avatar = "<img alt='{$alt}' src='{$profile_img}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
			}
		}
		return $avatar;
	}
	function setting_menu(){
		add_options_page( 'hotpack_social_login', __('Social Login Settings','hotpack'), 10, basename(__FILE__), array(&$this,'social_login_option_page') );
	}
	function social_login_option_page(){
		require_once(dirname(dirname(__FILE__)).'/html/social_login_option_page.php');
	}
	function register_setting(){
		register_setting( 'hotpack-social-login', 'hotpack_social_login' );
	}
	function social_login_form(){
		if(!is_user_logged_in()):
		?>
		<p>
		<?php
		$hotpack_social_login = get_option('hotpack_social_login');
		?>
		<?php if(isset($hotpack_social_login['naver']['used']) && $hotpack_social_login['naver']['used'] == 'enable' && isset($hotpack_social_login['naver']['consumer_key'])):?>
			<a href="<?php echo home_url('?hsl_login=naver');?>"><img src="<?php echo plugins_url( '_asset/images/naver_icon.png', dirname(__FILE__) );?>" alt="네이버 로그인 아이콘"></a></a>
		<?php endif;?>
		<?php if(isset($hotpack_social_login['daum']['used']) && $hotpack_social_login['daum']['used'] == 'enable' && isset($hotpack_social_login['daum']['consumer_key'])):?>
			<a href="<?php echo home_url('?hsl_login=daum');?>"><img src="<?php echo plugins_url( '_asset/images/daum_icon.png', dirname(__FILE__) );?>" alt="다음 로그인 아이콘"></a></a>
		<?php endif;?>
		<?php if(isset($hotpack_social_login['kakao']['used']) && $hotpack_social_login['kakao']['used'] == 'enable' && isset($hotpack_social_login['kakao']['consumer_key'])):?>
			<a href="https://kauth.kakao.com/oauth/authorize?client_id=<?php echo $hotpack_social_login['kakao']['consumer_key'];?>&redirect_uri=<?php echo urlencode(home_url('?hsl_callback=kakao'));?>&response_type=code"><img src="<?php echo plugins_url( '_asset/images/kakao_icon.png', dirname(__FILE__) );?>" alt="카카오 로그인 아이콘"></a></a>
		<?php endif;?>
		<?php if(isset($hotpack_social_login['facebook']['used']) && $hotpack_social_login['facebook']['used'] == 'enable' && isset($hotpack_social_login['facebook']['consumer_key'])):?>
			<a href="<?php echo home_url('?hsl_login=facebook');?>"><img src="<?php echo plugins_url( '_asset/images/wpzoom/facebook.png', dirname(__FILE__) );?>" alt="페이스북 로그인 아이콘"></a></a>
		<?php endif;?>
		<?php if(isset($hotpack_social_login['twitter']['used']) && $hotpack_social_login['twitter']['used'] == 'enable' && isset($hotpack_social_login['twitter']['consumer_key'])):?>
			<a href="<?php echo home_url('?hsl_login=twitter');?>"><img src="<?php echo plugins_url( '_asset/images/wpzoom/twitter.png', dirname(__FILE__) );?>" alt="트위터 로그인 아이콘"></a></a>
		<?php endif;?>
		<?php if(isset($hotpack_social_login['google']['used']) && $hotpack_social_login['google']['used'] == 'enable' && isset($hotpack_social_login['google']['consumer_key'])):?>
			<a href="<?php echo home_url('?hsl_login=google');?>"><img src="<?php echo plugins_url( '_asset/images/wpzoom/google.png', dirname(__FILE__) );?>" alt="구글 로그인 아이콘"></a></a>
		<?php endif;?>
		</p>
		<?php
		endif;
	}
	function social_login_init(){
		if(isset($_GET['hsl_login'])){// && !is_user_logged_in()){

			switch($_GET['hsl_login']){
				case 'naver':
					if(!class_exists('NaverOAuth'))
						require_once(dirname(__FILE__).'/class.naveroauth.php');
					session_start();
					$connection = new NaverOAuth($consumer_key, $consumer_secret);
					$connection->set_state();
					$request_token_info = $connection->getRequestToken($callback_url);
					$_SESSION['oauth_token'] = $token = $request_token_info['oauth_token'];
					$_SESSION['oauth_token_secret'] = $request_token_info['oauth_token_secret'];
					header('Location:'.$connection->authorizeURL().'&oauth_token='.$request_token_info['oauth_token']);
					//header('Location:'.$connection->authorizeURL().'&client_id='.$consumer_key);
					
					die();
					break;
				case 'facebook':
					if(!class_exists('Facebook')){
						require_once(dirname(__FILE__).'/facebook/facebook.php');
					}
					$hotpack_social_login = get_option('hotpack_social_login');
					$facebook = new Facebook(array(
					  'appId'  => $hotpack_social_login['facebook']['consumer_key'],
					  'secret' => $hotpack_social_login['facebook']['consumer_secret'],
					));
					$parameters = array(
							'scope'			=> 'email, public_profile, user_friends',
							'redirect_uri'	=> home_url('?hsl_callback=facebook'),
							'display'		=> 'page'
						);
					$loginUrl = $facebook->getLoginUrl($parameters);
					header('Location:'.$loginUrl);
					
					die();
					break;
				case 'twitter':
					if(!class_exists('TwitterOAuth'))
						require_once(dirname(__FILE__).'/class.twitteroauth.php');
					session_start();
					$twitter = new TwitterOAuth($consumer_key, $consumer_secret);
					$tokens = $twitter->requestToken( );
					$_SESSION['oauth_token'] = $token = $tokens['oauth_token'];
					$_SESSION['oauth_token_secret'] = $tokens['oauth_token_secret'];
					
					header('Location:'.$twitter->authorizeUrl( $tokens ));
					die();
					break;
				case 'google':
					if(!class_exists('GoogleOAuth'))
						require_once(dirname(__FILE__).'/class.googleoauth.php');
					session_start();
					$google = new GoogleOAuth($consumer_key, $consumer_secret,home_url('?hsl_callback=google'));
					$parameters = array(
							'scope' => 'profile https://www.googleapis.com/auth/plus.profile.emails.read',
							'access_type' => 'offline'
						);
					$loginUrl = $google->authorizeUrl($parameters);
					header('Location:'.$loginUrl);
					die();
					break;
			}
		}
	}
	function social_login_callback_init(){
		if(isset($_GET['hsl_callback'])){// && !is_user_logged_in()){

			switch($_GET['hsl_callback']){
				case 'naver':
					if(!class_exists('NaverOAuth'))
						require_once(dirname(__FILE__).'/class.naveroauth.php');
					@session_start();
					//print_r($_SESSION);
					$connection = new NaverOAuth($consumer_key, $consumer_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
					//print_r($connection);
					$access_token = $connection->getAccessToken($_GET['oauth_verifier']);
					
					$user_profile = $connection->get_user_profile();
					if(isset($user_profile['userID'])){
						$this->login($user_profile['userID'],$user_profile['user_email'], $user_profile['nickname'],$user_profile['profImg'],$_GET['hsl_callback']);	
					}
					
					die();
					break;
				case 'kakao':
					if(!class_exists('kakaoOAuth'))
						require_once(dirname(__FILE__).'/class.kakaooauth.php');
					$code = $_GET['code'];
					$connection = new KakaoOAuth($consumer_key,$code);
					$access_token = $connection->getAccessToken();
					$user_profile = $connection->get_user_profile($access_token);
					if(isset($user_profile['userID'])){
						$this->login($user_profile['userID'],$user_profile['user_email'], $user_profile['nickname'],$user_profile['profImg'],$_GET['hsl_callback']);	
					}
					die();
					break;
				case 'facebook':
					if(!class_exists('Facebook')){
						require_once(dirname(__FILE__).'/facebook/facebook.php');
					}
					$hotpack_social_login = get_option('hotpack_social_login');
					$facebook = new Facebook(array(
					  'appId'  => $hotpack_social_login['facebook']['consumer_key'],
					  'secret' => $hotpack_social_login['facebook']['consumer_secret'],
					));
					
					$access_token = $facebook->getAccessToken();
					
					$facebook->setAccessToken( $access_token );

					//$facebook->setExtendedAccessToken();echo 'asdf';
					$access_token = $facebook->getAccessToken();

					if( $access_token ){
						$facebook->setAccessToken( $access_token );
					}
					$facebook->setAccessToken( $access_token );

					$data = $facebook->api('/me'); 
					$user_profile = array(
				      'userID' => $data['id'],
				      'user_email'  => 'f-'.$data['email'] ,
				      'nickname' => $data['name'],
				      'profImg' => "https://graph.facebook.com/" . $data['id'] . "/picture?width=150&height=150",
				      'first_name'	=> $data['first_name'],
				      'last_name'	=> $data['last_name']
				    );

				    if(isset($user_profile['userID'])){
						$this->login($user_profile['userID'],$user_profile['user_email'], $user_profile['nickname'],$user_profile['profImg'],$_GET['hsl_callback'],$user_profile['first_name'],$user_profile['last_name']);	
					}
					die();
					break;
				case 'twitter':
					if(!class_exists('TwitterOAuth'))
						require_once(dirname(__FILE__).'/class.twitteroauth.php');
					@session_start();
					if(isset($_REQUEST['oauth_verifier'])){
						
						$twitter = new TwitterOAuth($consumer_key, $consumer_secret,$_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
						$access_token = $twitter->accessToken($_GET['oauth_verifier']);
						$response = $twitter->get( 'account/verify_credentials.json' );
						$user_profile = array(
					      'userID' => $response->id,
					      'user_email'  => 't-'.$response->screen_name.'@temp.com' ,
					      'nickname' => $response->name,
					      'profImg' => $response->profile_image_url
					    );
						if(isset($user_profile['userID'])){
							$this->login($user_profile['userID'],$user_profile['user_email'], $user_profile['nickname'],$user_profile['profImg'],$_GET['hsl_callback']);	
						}
					}
					die();
					break;
				case 'google':
					if(!class_exists('GoogleOAuth'))
						require_once(dirname(__FILE__).'/class.googleoauth.php');
					session_start();
					$google = new GoogleOAuth($consumer_key, $consumer_secret,home_url('?hsl_callback=google'));

					$code = (array_key_exists('code',$_REQUEST))?$_REQUEST['code'] : "";
					$google->authenticate( $code ); 
					$response = $google->api( "https://www.googleapis.com/plus/v1/people/me" );
					$user_profile = array(
					      'userID' => $response->id,
					      'user_email'  => 'g-'.$response->emails[0]->value ,
					      'nickname' => (property_exists($response,'displayName'))?$response->displayName:"",
					      'profImg' => (property_exists($response,'image'))?((property_exists($response->image,'url'))?substr($response->image->url, 0, -2)."200":''):'',
					      'first_name'	=> $response->name->givenName,
				      		'last_name'	=> $response->name->familyName
					    );
					if(isset($user_profile['userID'])){
						$this->login($user_profile['userID'],$user_profile['user_email'], $user_profile['nickname'],$user_profile['profImg'],$_GET['hsl_callback'],$user_profile['first_name'],$user_profile['last_name']);	
					}
					die();
					break;
			}
		}
	}
	function login($user_login, $user_email, $user_name,$profile_img, $type, $first_name = false,$last_name = false){
		$userdata = get_user_by("login", $user_login);

		if(!$userdata){
			$name_args = $this->get_name_parts($user_name);
			$insert_user_args = array(
									'user_login' 			=> $user_login,
									'user_email' 			=> $user_email,
									'user_pass'				=> wp_generate_password(),
									'first_name' 			=> isset($first_name) ? $first_name : $name_args["first_name"],
									'last_name' 			=> isset($last_name) ? $last_name : $name_args["last_name"],
									'show_admin_bar_front' 	=> false,
									'show_admin_bar_admin' 	=> false,
									'display_name'			=> $user_name,
									'user_nicename'			=> $user_name,
									'nickname'				=> $user_name

								);
			wp_insert_user($insert_user_args);
			$userdata = get_user_by('login', $user_login);
		}
		update_user_meta($userdata->ID,'logintype',$type);
		update_user_meta($userdata->ID,'profileimg',$profile_img);
		wp_set_current_user($userdata->ID, $user_login);
		wp_set_auth_cookie($userdata->ID);
		do_action('wp_login', $user_login,$userdata);
		wp_redirect(home_url());
		exit;
	}
	//이름 분리
	public static function get_name_parts($full_name){
		$full_name = trim($full_name);
		//외국인일 경우(이름구성이 알파벳만 있거나 알파벳+숫자로 되어 있으면 외국인 간주)
		if( ctype_alpha( preg_replace('/\s*/', '', $full_name) ) || ctype_alnum( preg_replace('/\s*/', '', $full_name) ) ){
			$arr = explode(' ', $full_name);
			$arr_length = count($arr);
			switch($arr_length){
				case 1:
					return array('first_name' => $full_name, 'last_name' => '');
				case 2:
					//이름 쪼개서 반환한다.
					//예외 : 일본인이 '성 이름' 순서로 입력했을 경우 성 이름이 바뀌어서 들어감, 입력 후 수작업 처리해주어야 함.
					return array('first_name' => $arr[0], 'last_name' => $arr[1]);
				default:
					//이름이 3조각이상 되면 마지막 단어(?)를 성으로 간주하고 나머지를 이름으로 처리
					$last = end($arr);
					return array('first_name' => trim(str_replace($last, '', $full_name)), 'last_name' => $last);
			}
		} else {
			//한글은 멀티바이트 문자열로 핸들링
			//PHP 설치시에 --enable-mbstring 옵션 필요
			$length = mb_strlen($full_name);
			switch($length){
				case 1:
					//성명자체가 한글자인 경우...없겠지만 혹시 몰라서 처리만 해둠
					return array('first_name' => $full_name, 'last_name' => '');
				case 2:
					return array('first_name' => mb_substr($full_name, 1, 1, 'UTF-8'), 'last_name' => mb_substr($full_name, 0, 1, 'UTF-8'));
				case 3:
					//예외 : 두글자 성이면서 이름이 외자인 경우는 특수한 케이스로 수작업처리해주어야 함...구분할 방법을 모르겠음...구분하는게 가능한가-_-;;
					//그냥 두글자 성이 포함으로 체크하면 안되는 것이, 성이 '독' 이고 이름이 '고탁'일 경우도 있을 수 있음...
					return array('first_name' => mb_substr($full_name, 1, 2, 'UTF-8'), 'last_name' => mb_substr($full_name, 0, 1, 'UTF-8'));
				case 4:
					//두글자 성 데이터
					$double_chars_last_names = array('강전', '남궁', '독고', '동방', '망절', '사공', '서문', '선우', '소봉', '장곡', '제갈', '황보');
					//이름이 4글자인 경우 앞 두글자가 두글자성 배열안에 있는지 확인
					if( in_array( mb_substr($full_name, 0, 2, 'UTF-8'), $double_chars_last_names ) ){
						return array('first_name' => mb_substr($full_name, 2, 2, 'UTF-8'), 'last_name' => mb_substr($full_name, 0, 2, 'UTF-8'));
					} else {
						return array('first_name' => mb_substr($full_name, 1, 3, 'UTF-8'), 'last_name' => mb_substr($full_name, 0, 1, 'UTF-8'));
					}
				default:
					//이외의 경우 첫글자를 성으로 간주하고 나머지를 이름으로 간주함.
					return array('first_name' => mb_substr($full_name, 1, $length-1, "UTF-8"), 'last_name' => mb_substr($full_name, 0, 1, "UTF-8"));
					//return array('first_name' => str_replace($full_name[0], '', $full_name), 'last_name' => $full_name[0]);
			}
		}
	}
}