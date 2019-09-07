<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function twitterLogin()
{
	$twitterLoginUrl='';
	require_once(APPPATH.'libraries/Twitter/autoload.php');

	use Twitter\src\TwitterOAuth;

	define('CONSUMER_KEY', 'EiVkFZYH22NUlnz3dw0Hqh4r1'); 	// add your app consumer key between single quotes
	define('CONSUMER_SECRET', 'BfvQK7piC9YpjvQ9Rdh1K3HvlIkIkfENXIGimvvDFyx1Xq5G6l'); // add your app consumer 																			secret key between single quotes
	define('OAUTH_CALLBACK', 'http://reesort.com/taxidio/share-iti-with-twitter'); // your app callback URL i.e. page 	

	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
	$twitterLoginUrl = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
	//echo $url;
	//echo "<a href='$url'><img src='twitter-login-blue.png' style='margin-left:4%; margin-top: 4%'></a>";
	//header("location:$url");
	if(!empty($twitterLoginUrl))
	{
		return $twitterLoginUrl;
	}
	return false;
}



?>
