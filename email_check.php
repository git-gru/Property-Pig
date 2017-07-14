<?php 
session_start();
include 'admin/json/settings.php'; 
    $obj1   = new commonFunctions();
    $dbh1    = $obj1->dbh;
    $status = false;
    $row    = '';
    $query1  = $dbh1->prepare("select count(user_id) from pig_user where email=:email");
    $query1->bindParam(":email",$_REQUEST['email']);
    $query1->execute();        
    $number_of_rows = $query1->fetchColumn(); 
    if($number_of_rows>0)
    {
        echo "1";
    }
    else
    {
        echo "0";
    }
 

?>