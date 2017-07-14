<?php
include ("twitter/connection.php");
 $hostname = 'localhost';
        $username = 'pp_j9f349imc9';
        $password = '~;lWBT35GD;{';
        $dbname     = 'pp_0cd249u34';
$dbhandle = mysql_connect($hostname, $username, $password); 
mysql_select_db($dbname,$dbhandle); 
if(!empty($_SESSION['access_token']))
{
    $check = mysql_query("SELECT * FROM `pig_user` WHERE `userName` = '" . $_SESSION['access_token']['screen_name'] . "'");
    if (mysql_num_rows($check) > 0) {
        $result = mysql_fetch_array($check);
        $uname = $_SESSION['access_token']['screen_name'];
        $query = "UPDATE pig_user SET Name = '" . $uname . "', userName = '" . $_SESSION['access_token']['screen_name'] . "' WHERE user_id = " . $result['user_id'];
        mysql_query($query);
        $user_id=  $result['user_id'];
    } else {
        $uname = $_SESSION['access_token']['screen_name'];
        $result = mysql_query("INSERT INTO pig_user(Name,username,tw_id,userStatus) 
                 VALUES('" . $uname . "','" . $uname . "','" . $_SESSION['access_token']['user_id'] . "',1)");
        $user_id = mysql_insert_id();

    }
    $_SESSION['user_id']=$user_id;
    $_SESSION['user_ids']=$user_id;
    
    
    echo"<script>window.location.href = 'https://www.propertypig.co.uk/Profile_complete.php';</script>";
        
    
    
}
else
{
    echo"<script>window.location.href = 'https://www.propertypig.co.uk/';</script>";
}

?>
