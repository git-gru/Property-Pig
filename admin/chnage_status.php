<?php 
session_start();
include 'json/settings.php'; 
include '../Appapi/push.php';
$user_id=$_REQUEST['user_id'];
$property_id=$_REQUEST['property_id'];
$status=$_REQUEST['status'];

ini_set("display_errors", "0"); error_reporting(0);

$obj2   = new commonFunctions();
$dbh2    = $obj2->dbh;
$row2   = '';
$query2  = $dbh2->prepare("delete from pig_status where user_id=:user_id and property_id=:property_id");
$query2->bindParam(":user_id",$user_id);
$query2->bindParam(":property_id",$property_id);
$query2->execute();

$query2  = $dbh2->prepare("insert into pig_status (`user_id`,`property_id`,`status`) values (:user_id,:property_id,:status)");
$query2->bindParam(":user_id",$user_id,PDO::PARAM_INT);
$query2->bindParam(":property_id",$property_id,PDO::PARAM_INT);
$query2->bindParam(":status",$status,PDO::PARAM_STR);
$query2->execute();


if($status=='New')
{
	$status_n =0;  
}
else if($status=='passed_to_exit_strategy')
{
	$status_n =1;
}
else if($status=='lost')
{
	$status_n =2;
}
else if($status=='sold')
{
	$status_n =3;
} 
	

$query3  = $dbh2->prepare("update pig_user_answer_rel set status=:status_n where property_id=:property_id");
$query3->bindParam(":status_n",$status_n,PDO::PARAM_STR);
$query3->bindParam(":property_id",$property_id,PDO::PARAM_INT);
 
$query3->execute();

$query2  = $dbh2->prepare("select devicetoken from pig_user where user_id=:user_id");
$query2->bindParam(":user_id",$user_id);
$query2->execute();
$row = $query2->fetch(PDO::FETCH_ASSOC);
if(!empty($row))
{
    $deviceToken=$row['devicetoken'];
    $notification="Admin has changed your property status to ".$status;
    to_sendpushnotification($notification, $deviceToken);
    
}   
        
echo "1";
       

?>