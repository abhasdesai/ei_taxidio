<?php
session_start();
require 'autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
	//In project use this session to change login header after successful login 
	$request_token = [];
	$request_token['oauth_token'] = $_SESSION['oauth_token'];
	$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
	$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_GET['oauth_verifier']));
	$_SESSION['twitter_access_token'] = $access_token;