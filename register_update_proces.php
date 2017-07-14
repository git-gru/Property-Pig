<?php 
session_start();
include 'admin/json/settings.php'; 
if($_REQUEST['txtname']!='' && $_REQUEST['txtphone']!='')
{
    $txtname=$_REQUEST['txtname'];
    $txtphone=$_REQUEST['txtphone'];
    $txtemail=$_REQUEST['txtemail'];
    $txtzip=$_REQUEST['txtzip'];
    $txtaddress=$_REQUEST['txtaddress'];
    $txtaddress1=$_REQUEST['txtaddress1'];
    $txtusername=$_REQUEST['txtusername'];
    $profile_status=$_REQUEST['profile_status'];
     if($rows['fb_id']!='' || $rows['tw_id']!='' || $rows['lk_id']!='')
    { }else
    {
        $txtpassword=base64_encode($_REQUEST['txtpassword']);
    }
    $obj1   = new commonFunctions();$i=1;
    $dbh    = $obj1->dbh;
    $status = false;
    $row    = '';
    $query  = $dbh->prepare("update pig_user set `Name`=:txtname,phoneNumber=:txtphone,`email`=:txtemail,`zipCode`=:txtzip,`Address`=:txtaddress,`Address1`=:txtaddress1,`userStatus`=1,`userName`=:txtusername,`profile_status`=:profile_status where `user_id`=:user_id");
    $query->bindParam(":txtname",$txtname,PDO::PARAM_STR); 
    $query->bindParam(":txtphone",$txtphone,PDO::PARAM_STR); 
    $query->bindParam(":txtemail",$txtemail,PDO::PARAM_STR); 
    $query->bindParam(":txtzip",$txtzip,PDO::PARAM_STR); 
    $query->bindParam(":txtaddress",$txtaddress,PDO::PARAM_STR); 
    $query->bindParam(":txtaddress1",$txtaddress1,PDO::PARAM_STR); 
    $query->bindParam(":txtusername",$txtusername,PDO::PARAM_STR); 
    $query->bindParam(":profile_status",$profile_status,PDO::PARAM_STR); 
    $query->bindParam(":user_id",$_SESSION['user_id'],PDO::PARAM_INT); 
    if($query->execute())
    {
        echo "1";
    }
    else
    {
        echo "0";
    }
    if($rows['fb_id']!='' || $rows['tw_id']!='' || $rows['lk_id']!='')
    { }else{
        $user_id=$_SESSION['user_id'];
        $query  = $dbh->prepare("update pig_user set `Password`=:txtpassword where `user_id`=:user_id");
        $query->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
        $query->bindParam(":txtpassword",$txtpassword,PDO::PARAM_INT); 
        $query->execute();
    }
}

?>