<?php 
session_start();
include 'admin/json/settings.php'; 
$temp = explode(".", $_FILES["file"]["name"]);
$property_id=$_REQUEST['property_id'];
$newfilename = round(microtime(true)) . '.' . end($temp);
move_uploaded_file($_FILES["file"]["tmp_name"], "assets/docs/" . $newfilename);
$obj2   = new commonFunctions();
$dbh    = $obj2->dbh;
$query  = $dbh->prepare("insert into pig_documents (`user_id`,`property_id`,`document`) values(:user_id,:property_id,:newfilename)");    
$query->bindParam(":user_id",$_SESSION['user_id'],PDO::PARAM_INT);
$query->bindParam(":property_id",$property_id,PDO::PARAM_INT); 
$query->bindParam(":newfilename",$newfilename,PDO::PARAM_STR);  
$query->execute();
echo("<script>alert('document upload successfully...');location.href = '".$base_url."upload.php?id=".$_REQUEST['id']."';</script>");
?>