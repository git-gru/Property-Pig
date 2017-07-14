<?php 
session_start();
include 'json/settings.php'; 
include '../Appapi/push.php';
$user_id=$_REQUEST['user_id'];
$property_id=$_REQUEST['property_id'];
$amount=$_REQUEST['amount'];
$obj2   = new commonFunctions();
$dbh2    = $obj2->dbh;
$row2   = '';

    $query2  = $dbh2->prepare("select * from pig_user_answer where property_id=:property_id");
$query2->bindParam(":property_id",$property_id);
    $query2->execute();
    $s=0;
    while($rows = $query2->fetch(PDO::FETCH_ASSOC))
    {
        if($s==0)
        {
            $property_name=$rows['answer'];
        }
        else if($s==1)
        {
            $property_code=$rows['answer'];
        }
    }

ini_set("display_errors", "0"); error_reporting(0);


$query2  = $dbh2->prepare("insert into pig_property_offer (`offerpropertyId`,`userid`,`offerPrice`) values (:property_id,:user_id,:amount)");
$query2->bindParam(":property_id",$property_id,PDO::PARAM_INT);
$query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
$query2->bindParam(":amount",$amount,PDO::PARAM_INT); 
$query2->execute();
if ($query2->execute()) 
{
    $notification="We've sent you a revised offer for ".$property_name.", ".$property_code;
    $query2  = $dbh2->prepare("insert into pig_notification (`user_id`,`property_id`,`notification`) values (:user_id,:property_id,:notification)");
    $query2->bindParam(":property_id",$property_id,PDO::PARAM_INT);
    $query2->bindParam(":user_id",$user_id,PDO::PARAM_INT); 
    $query2->bindParam(":notification",$notification,PDO::PARAM_STR); 
    $query2->execute();
    
    $query2  = $dbh2->prepare("select devicetoken from pig_user where user_id=:user_id");
$query2->bindParam(":user_id",$user_id);
    $query2->execute();
    $row = $query2->fetch(PDO::FETCH_ASSOC);
    if(!empty($row))
    {
        $deviceToken=$row['devicetoken'];
        to_sendpushnotification($notification, $deviceToken);
    }   
        
    echo "1";

}
 else {
     echo "0";
}
       

?>
