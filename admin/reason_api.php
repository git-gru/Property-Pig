<?php
include_once( 'json/class_functions.php' );
include_once( 'json/class_admin.php' );


$obj1 = new commonFunctions();
$dbh = $obj1->dbh;
$status = "false";
$option_array = json_decode($option_array);
$row = '';
$reason=$_REQUEST['txtarea'];
$query = $dbh->prepare("INSERT INTO `pig_reasons`(`reason`) VALUES(:reason)");
$query->bindParam(":reason",$reason,PDO::PARAM_STR); 
if ($query->execute()) {
    $status = "true";
}
echo $status;
?>