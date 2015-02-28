<style type="text/css">
div.hotpack_admin .col-2{
	width: 45%;
	float:left;
	position: relative;
	min-height: 1px;
	padding-right: 15px;
	padding-left: 15px;
}

div.hotpack_admin .panel{
	box-shadow: none;
	border-radius: 2px;
	margin-bottom: 20px;
	background-color: #fff;
	border: 1px solid #ccc;
	border-radius: 4px;
	-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
	box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
div.hotpack_admin .panel-header{
	border-color: #eeeff8;
	border-top-left-radius: 0;
	border-top-right-radius: 0;
	padding: 10px 15px;
	border-bottom: 1px solid #ccc;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	background:#0074a2;
	color:#fff;
	font-weight:700;
}
div.hotpack_admin .naver{
	background: #1ec800;
}
div.hotpack_admin .daum{
	background: #5b99fb;
}
div.hotpack_admin .kakao{
	background: #ffeb00;
	color:#3b3b3b;
}
div.hotpack_admin .nate{
	background: #ed1f01;
}
div.hotpack_admin .facebook{
	background: #45619d;
}
div.hotpack_admin .twitter{
	background: #55acee;
}
div.hotpack_admin .google{
	background: #4d4d4d;
}
div.hotpack_admin .panel-body{
	padding: 15px;
}
div.hotpack_admin .form-group{
	margin-bottom: 15px;
}
div.hotpack_admin label{
	display: inline-block;
	max-width: 100%;
	margin-bottom: 5px;
	font-weight: 600;
}
div.hotpack_admin .form-control{
	display:block;
	width:100%;
}
div.hotpack_admin .row:before{
	display: table;
	content: " ";
}
div.hotpack_admin .row:after{
	clear:both;
}
</style>
<div class="wrap hotpack_admin">
	<h2><?php echo __('Social Login Settings','hotpack');?></h2>
	<form action="options.php" method="post">
		<?php settings_fields( 'hotpack-social-login' );?>
		<?php
		$hotpack_social_login = get_option('hotpack_social_login');
		$defaults = array(
				'naver'	=> array(
						'used'	=> 'disable',
						'consumer_key'	=> '',
						'consumer_secret'	=> ''
					)
			);
		$hotpack_social_login = wp_parse_args( $hotpack_social_login, $defaults );
		?>
		<div class="row">
			<div class="col-2">
				<section class="panel">
					<header class="panel-header naver"><?php echo __('NAVER','hotpack');?></header>
					<div class="panel-body">
						<div class="form-group">
							<label for="naver_enable"><?php echo __('Used','hotpack');?></label>
							<select name="hotpack_social_login[naver][used]" class="form-control" id="naver_enable">
								<option value="disable"<?php selected( $hotpack_social_login['naver']['used'], 'disable' );?>><?php echo __('Disable','hotpack');?></option>
								<option value="enable"<?php selected( $hotpack_social_login['naver']['used'], 'enable' );?>><?php echo __('Enable','hotpack');?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="naver_consumer_key"><?php echo __('ClientID','hotpack');?></label>
							<input type="text" name="hotpack_social_login[naver][consumer_key]" class="form-control" id="naver_consumer_key" value="<?php echo $hotpack_social_login['naver']['consumer_key'];?>"/>
						</div>
						<div class="form-group">
							<label for="naver_consumer_secret"><?php echo __('ClientSecret','hotpack');?></label>
							<input type="text" name="hotpack_social_login[naver][consumer_secret]" class="form-control" id="naver_consumer_secret" value="<?php echo $hotpack_social_login['naver']['consumer_secret'];?>"/>
						</div>
						<div class="form-group">
							<label for="naver_consumer_callback"><?php echo __('Callback URL','hotpack');?></label>
							<input type="text" name="" class="form-control" id="naver_consumer_callback" value="<?php echo home_url('?hsl_callback=naver');?>" readonly/>
						</div>
					</div>
				</section>
			</div>
			<?php
			/*
			<div class="col-2">
				<section class="panel">
					<header class="panel-header daum"><?php echo __('DAUM','hotpack');?></header>
					<div class="panel-body">
						<div class="form-group">
							<label for="daum_enable"><?php echo __('Used','hotpack');?></label>
							<select name="hotpack_social_login[daum][used]" class="form-control" id="daum_enable">
								<option value="disable"<?php selected( $hotpack_social_login['daum']['used'], 'disable' );?>><?php echo __('Disable','hotpack');?></option>
								<option value="enable"<?php selected( $hotpack_social_login['daum']['used'], 'enable' );?>><?php echo __('Enable','hotpack');?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="daum_consumer_key"><?php echo __('ClientID','hotpack');?></label>
							<input type="text" name="hotpack_social_login[daum][consumer_key]" class="form-control" id="daum_consumer_key" value="<?php echo $hotpack_social_login['daum']['consumer_key'];?>"/>
						</div>
						<div class="form-group">
							<label for="daum_consumer_secret"><?php echo __('ClientSecret','hotpack');?></label>
							<input type="text" name="hotpack_social_login[daum][consumer_secret]" class="form-control" id="daum_consumer_secret" value="<?php echo $hotpack_social_login['daum']['consumer_secret'];?>"/>
						</div>
						<div class="form-group">
							<label for="daum_consumer_callback"><?php echo __('Callback URL','hotpack');?></label>
							<input type="text" name="" class="form-control" id="daum_consumer_callback" value="<?php echo home_url('?hsl_callback=daum');?>" readonly/>
						</div>
					</div>
				</section>
			</div>
			*/?>
			<?php
			/*
			<div class="col-2">
				<section class="panel">
					<header class="panel-header nate"><?php echo __('nate','hotpack');?></header>
					<div class="panel-body">
						<div class="form-group">
							<label for="nate_enable"><?php echo __('Used','hotpack');?></label>
							<select name="hotpack_social_login[nate][used]" class="form-control" id="nate_enable">
								<option value="disable"<?php selected( $hotpack_social_login['nate']['used'], 'disable' );?>><?php echo __('Disable','hotpack');?></option>
								<option value="enable"<?php selected( $hotpack_social_login['nate']['used'], 'enable' );?>><?php echo __('Enable','hotpack');?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="nate_consumer_key"><?php echo __('Consumer Key','hotpack');?></label>
							<input type="text" name="hotpack_social_login[nate][consumer_key]" class="form-control" id="nate_consumer_key" value="<?php echo $hotpack_social_login['nate']['consumer_key'];?>"/>
						</div>
						<div class="form-group">
							<label for="nate_consumer_secret"><?php echo __('key Secret','hotpack');?></label>
							<input type="text" name="hotpack_social_login[nate][consumer_secret]" class="form-control" id="nate_consumer_secret" value="<?php echo $hotpack_social_login['nate']['consumer_secret'];?>"/>
						</div>
						<div class="form-group">
							<label for="nate_consumer_callback"><?php echo __('Callback URL','hotpack');?></label>
							<input type="text" name="" class="form-control" id="nate_consumer_callback" value="<?php echo home_url('?hsl_callback=nate');?>" readonly/>
						</div>
					</div>
				</section>
			</div>
			*/?>
			<div class="col-2">
				<section class="panel">
					<header class="panel-header facebook"><?php echo __('facebook','hotpack');?></header>
					<div class="panel-body">
						<div class="form-group">
							<label for="facebook_enable"><?php echo __('Used','hotpack');?></label>
							<select name="hotpack_social_login[facebook][used]" class="form-control" id="facebook_enable">
								<option value="disable"<?php selected( $hotpack_social_login['facebook']['used'], 'disable' );?>><?php echo __('Disable','hotpack');?></option>
								<option value="enable"<?php selected( $hotpack_social_login['facebook']['used'], 'enable' );?>><?php echo __('Enable','hotpack');?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="facebook_consumer_key"><?php echo __('App ID','hotpack');?></label>
							<input type="text" name="hotpack_social_login[facebook][consumer_key]" class="form-control" id="facebook_consumer_key" value="<?php echo $hotpack_social_login['facebook']['consumer_key'];?>"/>
						</div>
						<div class="form-group">
							<label for="facebook_consumer_secret"><?php echo __('App Secret','hotpack');?></label>
							<input type="text" name="hotpack_social_login[facebook][consumer_secret]" class="form-control" id="facebook_consumer_secret" value="<?php echo $hotpack_social_login['facebook']['consumer_secret'];?>"/>
						</div>
						<div class="form-group">
							<label for="facebook_consumer_callback"><?php echo __('Callback URL','hotpack');?></label>
							<input type="text" name="" class="form-control" id="facebook_consumer_callback" value="<?php echo home_url('?hsl_callback=facebook');?>" readonly/>
						</div>
					</div>
				</section>
			</div>
			<div class="col-2">
				<section class="panel">
					<header class="panel-header twitter"><?php echo __('twitter','hotpack');?></header>
					<div class="panel-body">
						<div class="form-group">
							<label for="twitter_enable"><?php echo __('Used','hotpack');?></label>
							<select name="hotpack_social_login[twitter][used]" class="form-control" id="twitter_enable">
								<option value="disable"<?php selected( $hotpack_social_login['twitter']['used'], 'disable' );?>><?php echo __('Disable','hotpack');?></option>
								<option value="enable"<?php selected( $hotpack_social_login['twitter']['used'], 'enable' );?>><?php echo __('Enable','hotpack');?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="twitter_consumer_key"><?php echo __('Consumer Key (API Key)','hotpack');?></label>
							<input type="text" name="hotpack_social_login[twitter][consumer_key]" class="form-control" id="twitter_consumer_key" value="<?php echo $hotpack_social_login['twitter']['consumer_key'];?>"/>
						</div>
						<div class="form-group">
							<label for="twitter_consumer_secret"><?php echo __('Consumer Secret (API Secret)','hotpack');?></label>
							<input type="text" name="hotpack_social_login[twitter][consumer_secret]" class="form-control" id="twitter_consumer_secret" value="<?php echo $hotpack_social_login['twitter']['consumer_secret'];?>"/>
						</div>
						<div class="form-group">
							<label for="twitter_consumer_callback"><?php echo __('Callback URL','hotpack');?></label>
							<input type="text" name="" class="form-control" id="twitter_consumer_callback" value="<?php echo home_url('?hsl_callback=twitter');?>" readonly/>
						</div>
					</div>
				</section>
			</div>
			<div class="col-2">
				<section class="panel">
					<header class="panel-header google"><?php echo __('google','hotpack');?></header>
					<div class="panel-body">
						<div class="form-group">
							<label for="google_enable"><?php echo __('Used','hotpack');?></label>
							<select name="hotpack_social_login[google][used]" class="form-control" id="google_enable">
								<option value="disable"<?php selected( $hotpack_social_login['google']['used'], 'disable' );?>><?php echo __('Disable','hotpack');?></option>
								<option value="enable"<?php selected( $hotpack_social_login['google']['used'], 'enable' );?>><?php echo __('Enable','hotpack');?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="google_consumer_key"><?php echo __('Consumer Key (API Key)','hotpack');?></label>
							<input type="text" name="hotpack_social_login[google][consumer_key]" class="form-control" id="google_consumer_key" value="<?php echo $hotpack_social_login['google']['consumer_key'];?>"/>
						</div>
						<div class="form-group">
							<label for="google_consumer_secret"><?php echo __('Consumer Secret (API Secret)','hotpack');?></label>
							<input type="text" name="hotpack_social_login[google][consumer_secret]" class="form-control" id="google_consumer_secret" value="<?php echo $hotpack_social_login['google']['consumer_secret'];?>"/>
						</div>
						<div class="form-group">
							<label for="google_consumer_callback"><?php echo __('Callback URL','hotpack');?></label>
							<input type="text" name="" class="form-control" id="google_consumer_callback" value="<?php echo home_url('?hsl_callback=google');?>" readonly/>
						</div>
					</div>
				</section>
			</div>
			<div class="col-2">
				<section class="panel">
					<header class="panel-header kakao"><?php echo __('KAKAO','hotpack');?></header>
					<div class="panel-body">
						<div class="form-group">
							<label for="kakao_enable"><?php echo __('Used','hotpack');?></label>
							<select name="hotpack_social_login[kakao][used]" class="form-control" id="kakao_enable">
								<option value="disable"<?php selected( $hotpack_social_login['kakao']['used'], 'disable' );?>><?php echo __('Disable','hotpack');?></option>
								<option value="enable"<?php selected( $hotpack_social_login['kakao']['used'], 'enable' );?>><?php echo __('Enable','hotpack');?></option>
							</select>
						</div>
						<div class="form-group">
							<label for="kakao_consumer_key"><?php echo __('REST API KEY','hotpack');?></label>
							<input type="text" name="hotpack_social_login[kakao][consumer_key]" class="form-control" id="kakao_consumer_key" value="<?php echo $hotpack_social_login['kakao']['consumer_key'];?>"/>
						</div>
						<div class="form-group">
							<label for="kakao_consumer_callback"><?php echo __('Callback URL','hotpack');?></label>
							<input type="text" name="" class="form-control" id="kakao_consumer_callback" value="<?php echo home_url('?hsl_callback=kakao');?>" readonly/>
						</div>
					</div>
				</section>
			</div>
		</div>
		<?php submit_button();?>
	</form>
</div>