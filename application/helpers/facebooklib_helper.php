<?php

function facebookconfigdata()
{
	$CI = &get_instance();
	require_once(APPPATH.'libraries/facebook_login_with_php/config.php');
	require_once(APPPATH.'libraries/facebook_login_with_php/includes/functions.php');
	$fbuser = null;
	$loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$CI->config->item('redirecturl'),'scope'=>$CI->config->item('fbPermissions')));
	return $loginUrl;
	
}

function current_full_url()
{
    $CI =& get_instance();
	$url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}
/*

function getcaptcha()
{
	$CI = &get_instance();
	$CI->load->helper('captcha');
	$config = array(
	        'img_path'      => 'assets/captcha_images/',
            'img_url'       => site_url('assets/captcha_images').'/',
	        'font_path'     => './path/to/fonts/texb.ttf',
	        'img_width'     => '150',
	        'img_height'    => 30,
	        'expiration'    => 7200,
	        'word_length'   => 4,
	        'font_size'     => 20,
	        'img_id'        => 'Imageid',
	        'pool'          => '0123456789abcdefghijklmnopqrstuvwxyz',

	        // White background and border, black text and red grid
	        'colors'        => array(
	                'background' => array(255, 255, 255),
	                'border' => array(255, 255, 255),
	                'text' => array(0, 0, 0),
	                'grid' => array(255, 40, 40)
	        )
		);
	  $captcha = create_captcha($config);
	  $CI->session->unset_userdata('captchaCode');
      $CI->session->set_userdata('captchaCode',$captcha['word']);
      return $captcha['image'];
}
*/


?>