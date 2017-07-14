<?php
require("twitter/twitteroauth.php");
require 'twitter/twconfig.php';
session_start();

$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET); // No need to change anything in this line.

$request_token = $twitteroauth->getRequestToken('https://www.propertypig.co.uk/social_login/twitter/getTwitterData.php');

$_SESSION['oauth_token'] = $request_token['oauth_token']; // Save value in session variable
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

if ($twitteroauth->http_code == 200) {
    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
    header('Location: ' . $url);
} else {
    die('ERROR: Some thing goes wrong.');
}
?>
