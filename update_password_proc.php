<?php 
session_start();
include 'admin/json/settings.php'; 
if($_REQUEST['old_password']!='' && $_REQUEST['txtpassword']!='')
{
    $old_password=$_REQUEST['old_password'];
    $txtpassword=base64_encode($_REQUEST['txtpassword']);
    $obj1   = new commonFunctions();
    $dbh    = $obj1->dbh;
    $status = false;
    $row    = '';
    $user_id=$_SESSION['user_id'];
    $old_p =base64_encode($old_password);
    $query  = $dbh->prepare("UPDATE `pig_user` SET `Password` = :txtpassword WHERE `user_id` = :user_id and Password=:old_p");
    $query->bindParam(":txtpassword",$txtpassword,PDO::PARAM_STR);
    $query->bindParam(":user_id",$user_id,PDO::PARAM_INT);
    $query->bindParam(":old_p",$old_p,PDO::PARAM_INT);

    $query->execute();
    if ($query->rowCount() > 0) {
        echo "1";
    } else {
        echo "0";
    }
}
else 
{
    echo "0";
}
?>
