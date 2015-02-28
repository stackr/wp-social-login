<?php
$hotpack_social_login = get_option('hotpack_social_login');
$defaults = array(
		'twitter'	=> array(
				'used'	=> 'disable',
				'consumer_key'	=> '',
				'consumer_secret'	=> ''
			)
	);
$hotpack_social_login = wp_parse_args( $hotpack_social_login, $defaults );
$consumer_key = $hotpack_social_login['twitter']['consumer_key'];
$consumer_secret = $hotpack_social_login['twitter']['consumer_secret'];
$callback_url = site_url('?hsl_callback=twitter');
?>