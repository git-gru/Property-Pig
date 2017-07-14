<?php 
ob_start();
session_start();
include 'admin/json/settings.php'; 
if($_REQUEST)
{
    $id=$_SESSION['user_id'];
    $obj1   = new commonFunctions();
    $dbh    = $obj1->dbh;
    $status = false;
    $row    = '';
    $query1  = $dbh1->prepare("insert into pig_property_status(`user_id`,`property_id`,`status_reason`,`status`) values(:id,:property_id,:reason,:status)");
 
    $query3->bindParam(":id",$id,PDO::PARAM_INT);
    $query3->bindParam(":property_id",$_REQUEST['property_id'],PDO::PARAM_INT);
    $query3->bindParam(":reason",$_REQUEST['reason'],PDO::PARAM_STR); 
    $query3->bindParam(":status",$_REQUEST['status'],PDO::PARAM_INT); 


    $query1->execute();
    $reasonId = $dbh1->lastInsertId();
    if($reasonId)
        echo "1";
    else
        echo "0";
}

?>