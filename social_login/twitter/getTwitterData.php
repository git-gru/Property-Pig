<?php

require("twitter/twitteroauth.php");
require 'twitter/twconfig.php';
require 'twitter/functions.php';
session_start();

if (!empty($_GET['oauth_verifier']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])) {

    $twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    
    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);
    $_SESSION['access_token'] = $access_token;

    $user_info = $twitteroauth->get('account/verify_credentials');

    if (isset($user_info->error)) {
        header('Location: login-twitter.php');
    } else {
	   $twitter_otoken=$_SESSION['oauth_token'];
	   $twitter_otoken_secret=$_SESSION['oauth_token_secret'];
	   $email='';
        $uid = $user_info->id;
        $username = $user_info->name;
        $user = new User();
        $userdata = $user->checkUser($uid, 'twitter', $username,$email,$twitter_otoken,$twitter_otoken_secret);
        if(!empty($userdata)){
            session_start();
            $_SESSION['id'] = $userdata['id'];
			$_SESSION['oauth_id'] = $uid;
            $_SESSION['username'] = $userdata['username'];
            $_SESSION['oauth_provider'] = $userdata['oauth_provider'];
        }
    }
} else {
    // Something's missing, go back to square 1
    header('Location: login-twitter.php');
}
?>
