<?php 
session_start();
include 'admin/json/settings.php'; 
if($_REQUEST['id'])
{
    $parent_id=$_REQUEST['parent_id'];
    $obj2   = new commonFunctions();
    $dbh    = $obj2->dbh;
    $query  = $dbh->prepare("select document from pig_documents where id=:id");
    $query->bindParam(":id",$_REQUEST['id']);
    $query->execute();
    $rows_document = $query->fetch(PDO::FETCH_ASSOC);
    if(!empty($rows_document))
    {
        @unlink("assets/docs/".$rows_document['document']);
    }
    $query  = $dbh->prepare("delete from pig_documents where id=:id");
    $query->bindParam(":id",$_REQUEST['id']);
    $query->execute();
    echo("<script>alert('Delete Successfully');location.href = '".$base_url."upload.php?id=".$parent_id."';</script>"); 
}
else
{
   echo("<script>alert('Error Found!!!');location.href = '".$base_url."upload.php?id=".$parent_id."';</script>"); 
}



//
//$temp = explode(".", $_FILES["file"]["name"]);
//$property_id=$_REQUEST['property_id'];
//$newfilename = round(microtime(true)) . '.' . end($temp);
//move_uploaded_file($_FILES["file"]["tmp_name"], "assets/docs/" . $newfilename);
//$obj2   = new commonFunctions();
//$dbh    = $obj2->dbh;
//$query  = $dbh->prepare("insert into pig_documents (`user_id`,`property_id`,`document`) values('".$_SESSION['user_id']."','".$property_id."','".$newfilename."')");
//$query->execute();
//echo("<script>alert('document upload successfully...');location.href = '".$base_url."upload.php';</script>");
?>