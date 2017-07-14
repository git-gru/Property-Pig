<?php

require 'connection.php';

class User {

    function checkUser($uid,$oauth_provider,$username,$email,$twitter_otoken,$twitter_otoken_secret) 
	{
        $query = mysql_query("SELECT * FROM `pig_user` WHERE userName = '$username'") or die(mysql_error());//AND tw_id  = '$uid'
        $result = mysql_fetch_array($query);
        if (!empty($result)) {
            # User is already present          
//            header('Location: welcome.php');
        } else {
            #user not present. Insert a new Record
           header('Location: register_twitter.php?oauth_provider='.$oauth_provider.'&uid='.$uid.'&username='.$username.'&email='.$email);
        }
        return $result;
    }
}

?>
