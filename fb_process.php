<?php 
session_start();
include 'admin/json/settings.php'; 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
if($_REQUEST['fb_id']!='' && $_REQUEST['name']!='')
{
    $obj1   = new commonFunctions();
    $dbh1    = $obj1->dbh;
    $status = false;
    $row    = '';
      $where='';
      if($_REQUEST['email']!='')
      {
          $where.="`email`='".$_REQUEST['email']."' || ";
      }
      $query1= $dbh1->prepare("select * from pig_user where $where `fb_id`=:fb_id");
      $query1->bindParam(":fb_id",$_REQUEST['fb_id']);
      $query1->execute();
      if ($query1->rowCount()>0) 
      {
          $rows_user_deatils = $query1->fetch(PDO::FETCH_ASSOC);
          
          $query1 = $dbh1->prepare("UPDATE `pig_user` SET `Name`=:name,`email`=:email WHERE `fb_id`=:id" );
          $query1->bindParam(":name",$_REQUEST['name'],PDO::PARAM_STR);
          $query1->bindParam(":email",$_REQUEST['email'],PDO::PARAM_STR); 
          $query1->bindParam(":id",$_REQUEST['fb_id'],PDO::PARAM_STR); 

          $query1->execute(); 
          $user_id = $rows_user_deatils['user_id'];
      }
      else
      {
          $query1  = $dbh1->prepare("insert into pig_user(`Name`,`email`,`userStatus`,`fb_id`) values(:name,:email,1,:id)");

          $query1->bindParam(":name",$_REQUEST['name'],PDO::PARAM_STR);
          $query1->bindParam(":email",$_REQUEST['email'],PDO::PARAM_STR); 
          $query1->bindParam(":id",$_REQUEST['fb_id'],PDO::PARAM_STR); 

          $query1->execute(); 
          $user_id = $dbh1->lastInsertId();
      }
      $_SESSION['user_id']=$user_id;
      echo "1";
  
}

?>
