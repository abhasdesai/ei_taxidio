<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function googleLogin()
{
	$googleloginUrl='';
	require_once(APPPATH.'libraries/Google/autoload.php');
	$redirect_uri=site_url("rfun");
	//echo $redirect_uri;die;
	$client = new Google_Client();
	$client->setClientId('302599165572-q1lq74hg358g2va7i8p4hqlvhuc1c98i.apps.googleusercontent.com');
	$client->setClientSecret('o8vNEL_H91RHn-T3iLLJOb8h');
	$client->setRedirectUri($redirect_uri);
	$client->addScope("email");
	$client->addScope("profile");
	$service = new Google_Service_Oauth2($client);

	if (isset($_GET['code'])) 
	{
	  $client->authenticate($_GET['code']);
	  $_SESSION['access_token'] = $client->getAccessToken();
	  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	  exit;
	}

	if (isset($_SESSION['access_token']) && $_SESSION['access_token']) 
	{
	 	 $client->setAccessToken($_SESSION['access_token']);
	} 
	else 
	{
		$data['authUrl']= $client->createAuthUrl();
	}

	
	if (isset($data['authUrl']))
	{
		$googleloginUrl=$data['authUrl'];
	}
	/*else
	{
		//$user = $service->userinfo->get();
		//echo "<pre>";print_r($user);die;
		
	} */
	//echo $googleloginUrl;die;
	return $googleloginUrl;

	
}



?>
