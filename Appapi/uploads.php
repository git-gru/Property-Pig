<?php
include_once('class_functions.php');

    $userid=$_REQUEST['userid'];
    $propertyid=$_REQUEST['propertyid'];
    $obj1   = new commonFunctions();
    $dbh    = $obj1->dbh;

    $file   = $_FILES["File"]["name"];
    $random = rand(0,999);
    $file_name  = $random.$file;

    $move= move_uploaded_file($_FILES["File"]["tmp_name"],"../assets/docs/" . $file_name);
    if($move == true)
    {
        $query1  = $dbh->prepare("insert into pig_documents(`user_id`,`document`,`property_id`) values(:userid,:file_name,:propertyid')");

        $query1->bindParam(":file_name",$file_name,PDO::PARAM_STR);
$query1->bindParam(":userid",$userid,PDO::PARAM_INT); 
$query1->bindParam(":propertyId",$propertyId,PDO::PARAM_INT);



        $query1->execute();
        echo  $file_name ;
    }

?>