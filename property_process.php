<?php 
session_start();
include 'admin/json/settings.php'; 
if($_REQUEST)
{
    $obj1   = new commonFunctions();$i=1;
    $dbh    = $obj1->dbh;
    if($_REQUEST['status']==3)
    {
        $query  = $dbh->prepare("UPDATE `pig_notification` SET `status` = :status WHERE `user_id` = :user_id and property_id=:propertyId");

        $query->bindParam(":status",$_REQUEST['status'],PDO::PARAM_INT); 
        $query->bindParam(":user_id",$_SESSION['user_id'],PDO::PARAM_INT); 
        $query->bindParam(":propertyId",$_REQUEST['property_id'],PDO::PARAM_INT);
        $query->execute();
        
        $query  = $dbh->prepare("UPDATE `pig_user_answer_rel` SET `rate4` = :offer WHERE `property_id` = :propertyId and user_id=:user_id");
        $query->bindParam(":offer",$_REQUEST['offer'],PDO::PARAM_STR); 
        $query->bindParam(":user_id",$_SESSION['user_id'],PDO::PARAM_INT); 
        $query->bindParam(":propertyId",$_REQUEST['property_id'],PDO::PARAM_INT);
        $query->execute();
    }
    else if($_REQUEST['status']==4)
    {
        $query  = $dbh->prepare("UPDATE `pig_notification` SET `status` = :status WHERE `user_id` = :user_id and property_id= :propertyId");
        $query->bindParam(":status",$_REQUEST['status'],PDO::PARAM_INT); 
        $query->bindParam(":user_id",$_SESSION['user_id'],PDO::PARAM_INT); 
        $query->bindParam(":propertyId",$_REQUEST['property_id'],PDO::PARAM_INT);
        $query->execute();
    }
    echo "1";
    
}
else
{
    echo "0";
}

?>