<?php

session_start();

require_once 'src/twitter.class.php';

// ENTER HERE YOUR CREDENTIALS (see readme.txt)
$access_token = $_SESSION['twitter_access_token'];

$consumerKey="EiVkFZYH22NUlnz3dw0Hqh4r1";
$consumerSecret="BfvQK7piC9YpjvQ9Rdh1K3HvlIkIkfENXIGimvvDFyx1Xq5G6l";
$accessToken=$access_token['oauth_token'];
$accessTokenSecret=$access_token['oauth_token_secret'];
// ENTER HERE YOUR CREDENTIALS (see readme.txt)
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$user_trip_name=$_SESSION['itinerary_data']['user_trip_name'];
$share_url=$_SESSION['itinerary_data']['share_url'];
$send_data="I just made my travel itinerary “".ucwords($user_trip_name)."”. Take a look and let me know what you think!\n\n";
$send_data .="$share_url";
$send_data .="\n\nShared From: @taxidiotravel";
try {
	$tweet = $twitter->send($send_data); // you can add $imagePath or array of image paths as second argument

} catch (TwitterException $e) {
	$_SESSION['send_error']=$e->getMessage();
}
